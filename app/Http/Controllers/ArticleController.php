<?php
namespace App\Http\Controllers;

use App\Repositories\ArticlePinPositionRepository;
use Kurio\Common\Repositories\Topic\TopicRepository;
use Kurio\Common\Libraries\Extractor\Jsonifier;
use App\Repositories\ArticleElasticRepository;
use App\Repositories\AccessTokenRepository;
use Kurio\Common\Libraries\Image\HasImage;
use App\Repositories\ApiArticleRepository;
use Illuminate\Foundation\Application;
use App\Repositories\ImageRepository;
use Kurio\Common\Helpers\Url;
use Illuminate\Http\Request;
use Mockery\CountValidator\Exception;
use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Kurio\Common\Presenters\ImagePresentable;

class ArticleController extends Controller
{
    use HasImage;
    use ImagePresentable;

    protected $image_repository;
    protected $access_token;
    protected $jsonifier;
    protected $articles;
    protected $articles_pin_position;
    protected $topics;
    protected $app;

    public function __construct
    (
        ImageRepository $imageRepository,
        ApiArticleRepository $articles_repository,
        ArticlePinPositionRepository $articles_pin_position_repository,
        AccessTokenRepository $access_token,
        TopicRepository $topics_repository,
        Jsonifier $jsonifier,
        Application $app
    )
    {
        $this->image_repository = $imageRepository;
        $this->articles = $articles_repository;
        $this->articles_pin_position = $articles_pin_position_repository;
        $this->topics = $topics_repository;
        $this->access_token = $access_token;
        $this->jsonifier = $jsonifier;
        $this->app = $app;
    }

    public function getList(ArticleElasticRepository $elastic, Request $request)
    {
        $cursor = $request->input('cursor');
        $size = $request->input('size', 20);
        if ($request->input('search')) {
            $search = strip_tags(trim($request->input('search')));
            if (is_numeric($search)) {
                $param['id'] = $search;
            } elseif ($this->isSearchingUrl($search)) {
                $param['url'] = Url::cleanUrl($search);
            } else {
                $param = [
                    'keyword' => $search,
                    'topic' => $request->input('topic'),
                    'pinned' => $request->input('pinned')
                ];
            }
        } else {
            $param = [];
            if ($request->input('topic')) {
                $param['topic'] = $request->input('topic');
            }
            if ($request->input('pinned')) {
                $param['pinned'] = $request->input('pinned');
            }
        }
        $result = $elastic->search($param, $size, $cursor);

        if (empty($result)) {
            return response()->json(['data' => $result], 200);
        }
        $cursor += $size;
        $response = [];

        foreach ($result as $value) {
            $topics = [];
            foreach ($value->topic_ids as $topic_id) {
                echo $topic_id."-";
                $topics[] = ['topic' => $this->topics->findById($topic_id)];
            }
            $response[] = ['id' => $value->id,
                'title' => $value->title,
                'thumbnail' => $value->thumbnail,
                'source' => parse_url($value->url, PHP_URL_HOST),
                'pinned' => (bool)$value->pinned,
                'url' => $value->url,
                'pinned_until' => $value->pinned_until,
                'topics' => $topics];
        }
        $pagination = [
            'cursor' => $cursor,
            'next_url' => \Request::url() . '?' . http_build_query(compact('search', 'cursor', 'size'))
        ];
        return response()->json(['data' => $response, 'pagination' => $pagination], 200);
    }

    protected function isSearchingUrl($keyword)
    {
        if (strpos($keyword, 'http')) {
            $keyword = 'http://' . ltrim($keyword, '/');
        }
        return filter_var($keyword, FILTER_VALIDATE_URL);

    }

    public function destroy(ArticleElasticRepository $elastic, $id)
    {
        try {
            $article = $this->articles->findById($id);
            $delete = $this->articles->delete($article);
            $delete_elastic = $elastic->destroy($id);

            if ($delete && $delete_elastic) {
                return response()->json(['success' => 'Article ' . $article->title . ' has been deleted'], 200);
            }
            return response()->json(['error' => 'Article error to delete'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Article Not Found '], 404);
        }
    }

    public function pin(Request $request, $id)
    {
        try {
            $article_pin_position = $this->articles_pin_position->findByArticleId($id);

            $topic = $this->articles->getTopicByArticleId($id);

            $data['id_article'] = $id;
            $data['id_topic'] = $topic->id;
            $data['position'] = $request->input('position');
            $data['duration'] = $request->input('duration');
            $data['deleted_at'] = null;

            if ($request->has('start_date') and $request->has('end_date')) {
                $data['start_date'] = Carbon::createFromTimestamp($request->input('start_date'))->toDateTimeString();
                $data['end_date'] = Carbon::createFromTimestamp($request->input('end_date'))->toDateTimeString();
            } elseif ($request->has('duration')) {
                $data['start_date'] = Carbon::now();
                $data['end_date'] = Carbon::now()->addMinutes($data['duration']);
            }

            if ($request->has('target') and $request->input('target') == 'top_stories') {
                $isTopStoryExists = $this->articles_pin_position->isExistsTopStory($data['position'], $data['start_date'], $data['end_date']);
                if ($isTopStoryExists) {
                    return response()->json(['error' => 'Already pinned at same time and same position'], 422);
                }
                if ($article_pin_position == null) {
                    $pin = $this->articles_pin_position->addPinTopStory($data);
                    $pinnedStatus = $this->articles->updatePinnedStatus($this->articles->findById($id), 1);
                    if ($pin and $pinnedStatus) {
                        return response()->json(['success' => 'Article ID ' . $id . ' has been pinned and top story'], 200);
                    }
                    return response()->json(['error' => 'Failed to update article'], 400);
                } else {
                    $data['is_top_story'] = $article_pin_position->is_top_story;
                    $pin = $this->articles_pin_position->pinTopStory($article_pin_position, $data);
                    $pinnedStatus = $this->articles->updatePinnedStatus($this->articles->findById($id), 1);
                    if ($pin and $pinnedStatus) {
                        return response()->json(['success' => 'Article ID ' . $id . ' has been pinned and top story'], 200);
                    }
                    return response()->json(['error' => 'Failed to update article'], 400);
                }
            } else {
                $isTopicExists = $this->articles_pin_position->isExistsTopic($data['id_topic'], $data['position'], $data['start_date'], $data['end_date']);
                if ($isTopicExists) {
                    return response()->json(['error' => 'Already pinned at same time and same position'], 422);
                }
                if ($article_pin_position == null) {
                    $pin = $this->articles_pin_position->addPin($data);
                    $pinnedStatus = $this->articles->updatePinnedStatus($this->articles->findById($id), 1);
                    if ($pin and $pinnedStatus) {
                        return response()->json(['success' => 'Article ID ' . $id . ' has been pinned'], 200);
                    }
                    return response()->json(['error' => 'Failed to update article'], 400);
                } else {
                    $data['is_top_story'] = $article_pin_position->is_top_story;
                    $pin = $this->articles_pin_position->pin($article_pin_position, $data);
                    $pinnedStatus = $this->articles->updatePinnedStatus($this->articles->findById($id), 1);
                    if ($pin and $pinnedStatus) {
                        return response()->json(['success' => 'Article ID ' . $id . ' has been pinned'], 200);
                    }
                    return response()->json(['error' => 'Failed to update article ya'], 400);
                }
            }
        }
        catch (\Exception $e) {
            return response()->json(['error' => 'Article Not Found '.$e], 404);
        }
    }

    public function deletePin($id)
    {
        try {
            $delete_pin = $this->articles_pin_position->deletePin($id);
            $pinnedStatus = $this->articles->updatePinnedStatus($this->articles->findById($id), 0);
            if ($delete_pin and $pinnedStatus) {
                return response()->json(['success' => 'Deleted pin Article ID ' . $id], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Article Not Found '.$e], 404);
        }
    }

    public function getTopStoriesPinnedArticle(Request $request)
    {
        $cursor = $request->input('cursor');
        $size = $request->input('size', 20);

        $data = $this->articles_pin_position->findTopStoriesPinnedArticle($cursor, $size);

        $response = [];
        foreach ($data as $key) {
            $article = $this->articles->findById($key->id_article);
            $source = $this->articles->getSourceByArticleId($key->id_article);
            //$topic = $this->topics->findById($key->id_topic);

            $thumbnail =  $this->presentImageObject($article->thumbnail);

            $response[] = [
                'title' => $article->title,
                'excerpt' => $article->excerpt,
                'url' => $article->url,
                'thumbnail' => $thumbnail,
                'source' => array(
                    'id' => $source->id,
                    'name' => $source->name,
                    'url' => $source->url,
                    'icon' => 'https://plus.google.com/_/favicon?domain='.parse_url($source->url, PHP_URL_HOST)
                ),
                'id' => $article->id,
                'timestamp' => strtotime($article->published_at),
                'type' => 'article',
                //'topic' => $topic->name
            ];
        }
        $cursor += $size;
        $pagination = [
            'cursor' => $cursor,
            'next_url' => \Request::url() . '?' . http_build_query(compact('cursor', 'size'))
        ];

        return \Response::json(array(
            'error' => false,
            'message' => 'Get pinned article success',
            'data' => $response,
            'pagination' => $pagination),
            200
        );
    }

    public function getPinnedArticleByTopic(Request $request, $topic_id)
    {
        $cursor = $request->input('cursor');
        $size = $request->input('size', 20);

        $data = $this->articles_pin_position->findPinnedArticleByTopic($topic_id, $cursor, $size);
        $topic = $this->topics->findById($topic_id);

        $response = [];
        foreach ($data as $key) {
            $article = $this->articles->findById($key->id_article);
            $source = $this->articles->getSourceByArticleId($key->id_article);

            $thumbnail =  $this->presentImageObject($article->thumbnail);

            $response[] = [
                'title' => $article->title,
                'excerpt' => $article->excerpt,
                'url' => $article->url,
                'thumbnail' => $thumbnail,
                'source' => array(
                    'id' => $source->id,
                    'name' => $source->name,
                    'url' => $source->url,
                    'icon' => 'https://plus.google.com/_/favicon?domain='.parse_url($source->url, PHP_URL_HOST)
                ),
                'id' => $article->id,
                'timestamp' => strtotime($article->published_at),
                'type' => 'article'
            ];
        }

        $info = [
            'name' => $topic->name,
            'type' => 'topic',
            'id' => $topic_id
        ];

        $cursor += $size;
        $pagination = [
            'cursor' => $cursor,
            'next_url' => \Request::url() . '?' . http_build_query(compact('cursor', 'size'))
        ];

        return \Response::json(array(
            'error' => false,
            'message' => 'Get pinned article success',
            'data' => $response,
            'info' => $info,
            'pagination' => $pagination),
            200
        );
    }


    public function view($id)
    {
        try {
            $data['article'] = $this->articles->findById($id);

            $topics = [];

            foreach ($data['article']->topics()->get() as $article_topic => $topic) {
                $topics[] = ['id' => $topic->id, 'name' => $topic->name, 'created_at'=>$topic->created_at->toDateTimeString(),
                    'updated_at'=>$topic->updated_at->toDateTimeString()];
            }

            if (empty($data['article']->thumbnail)) {
                $thumbnail = new \stdClass;
            } else {
                $thumbnail = $this->presentImageObject($data['article']->thumbnail);
            }

            $response[] = [
                'id' => $data['article']->id,
                'title' => $data['article']->title,
                'content' => json_decode($data['article']->json),
                'thumbnail' => $thumbnail,
                'source' => parse_url($data['article']->url, PHP_URL_HOST),
                'pinned' => (bool)$data['article']->pinned,
                'url' => $data['article']->url,
                'topics' => $topics
            ];

            return \Response::json(array(
                'error' => false,
                'message'=>'Get article by id success',
                'data' => $response),
                200
            );

        } catch (\Exception $e) {
            return response()->json(['error' => 'Article Not Found : '.$e], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $article = $this->articles->findById($id);

            $data['title'] = $request->input('title');
            $data['topics'] = $request->input('topics');

            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'topics' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => 'title & topic can not empty'], 200);
            }
            $article = $this->articles->update($article, $data);
            return response()->json(['success' => 'Article ' . $article->   title . ' has been update'], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Redundant topics in an article'], 500);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Article not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error while update an article ' . $article->title], 500);
        }
    }

    protected function checkInput($input)
    {
        $data = $input;
        $data['url'] = Url::cleanUrl($input['url']);
        $data['thumbnail'] = $this->image_repository->upload($input['thumbnail']);

        $data ['thumbnail'] = 'http://ip:8082/img'.$data['thumbnail'];

        $json = $this->jsonifier->jsonify($input['content']);
        $data['json'] = json_encode($json);

        if (!empty($input['pinned_until'])) {
            $data['pinned_until'] = $input['pinned_until'];
        }
        return $data;
    }

    public function postSubmit(Request $request)
    {
        $data = $this->checkInput($request->all());
        $token = $request->header('Authorization');
        $data['curated_by'] = $this->access_token->findToken($token)->admin_id;

        if (!empty($data['article_id'])) {
            $article = $this->articles->findById($data['article_id']);

            $article = $this->articles->update($article, $data);

        } else {
            $article = $this->articles->getByUrl($data['url']);
            if (!empty($article)) {
                $article = $this->articles->update($article, $data);
            } else {
                $article = $this->articles->create($data);
            }
        }
        if ($article) {
            return response()->json(['success' => 'Article ' . $article->title . ' has been submitted'], 200);
        }
        return response()->json(['error' => 'Error while submit an article'], 500);
    }

}