<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Kurio\Common\Helpers\Url;
use App\Commands\SendPushNotification;
use App\Repositories\PushNotifRepository;
use Kurio\Common\Repositories\Article\DbArticleRepository;
use GuzzleHttp\Client;

class PushNotifController extends Controller
{
    protected $push_notif_repository;
    protected $article;
    public function __construct(
        PushNotifRepository $push_notif_repository,
        DbArticleRepository $article
    )
    {
        $this->push_notif_repository = $push_notif_repository;
        $this->article = $article;
    }
    public function getList()
    {
        $data = $this->push_notif_repository->getList(10);
        foreach ($data as $key) {
            $response[] = [
                'id' => $key->id,
                'title' => $key->title,
                'object_id' => $key->object_id,
                'image' => $key->image,
                'pushed_at' => strtotime($key->pushed_at)
            ];
        }
        return response()->json(['data' => $response], 200);
    }

    public function create(Request $request)
    {
        $post = $request->all();
        $article = $this->article->getByUrl(Url::cleanUrl(($post['url'])));
        $topic = $this->article->getTopicByArticleId($article->id);

        $data['title'] = $post['message'];
        $data['type'] = 'article';
        $data['object_id'] = $article->id;
        $data['target'] = "kurio://article/$article->id";
        $data['image'] = $article->thumbnail;
        $data['pushed_at'] = $request->input('pushed_at');

        if (empty($request['pushed_at'])) {
            $push_time = null;
        }

        try {
            $client = new Client();

            $res = $client->post($this->getPapuaHost().'/push', [
                'body' => [
                    'title' => $data['title'],
                    'type' => $data['type'],
                    'object_id' => $data['object_id'],
                    'target' => $data['target'],
                    'axis' => [
                        'type'=>$topic->name,
                        'id'=>$topic->id
                    ],
                    'image'=>$data['image']
                ]
            ]);

            if ($res->getStatusCode()==201) {

                $new_notif = $this->push_notif_repository->create($data);
                if (!$new_notif) {
                    return response()->json(['error' => 'An error occured while create push notification'], 500);
                }
                return response()->json(['success' => 'Push Notification  ' . $post['message'] . ' has been created'], 200);
            } else {
                return response()->json(['error' => 'An error occured while create push notification'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send Push Notification '.$e], 500);
        }
    }

    public function getPapuaHost()
    {
        return \Config::get('papua.host');
    }
    
    public function create2(Request $request)
    {
        $post = $request->all();

        //var_dump(Url::cleanUrl(($post['url'])));

        $article = $this->article->getByUrl(Url::cleanUrl(($post['url'])));

        //var_dump($article);

        $data['title'] = $post['message'];
        $data['type'] = 'article';
        $data['object_id'] = $article->id;
        $data['target'] = "kurio://article/$article->id";
        $data['image'] = $article->thumbnail;


        $data['pushed_at'] = $request->input('pushed_at');
        if (empty($request['pushed_at'])) {
            $push_time = null;
        }
        $push_time = ($request['pushed_at']) ? ($push_time = $request['pushed_at']) : $push_time = null;
        $new_notif = $this->push_notif_repository->create($data);

        if (!$new_notif) {
            return response()->json(['error' => 'An error occured while create push notification'], 500);
        }
        try {
            $this->dispatch(new SendPushNotification($new_notif, $push_time));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send Push Notification '.$e], 500);
        }
        return response()->json(['success' => 'Push Notification  ' . $post['message'] . ' has been created'], 200);
    }

    public function update($id, Request $request)
    {
        try {
            $push = $this->push_notif_repository->findById($id);

            $post = $request->all();

            $data['title'] = $request->input('message');

            $push = $this->push_notif_repository->update($push, $data);

            $post['pushed_at'] = $request->input('pushed_at');
            if ($post['pushed_at'] != $push['pushed_at']) {
                $push = $this->changePushTime($id, $post['pushed_at']);
            }
            if (!$push) {
                return response()->json(['error' => 'Error while updating push notification'], 500);
            }
            return response()->json(['success' => 'Push Notification  ' . $post['message'] . ' has been update'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Push Notification Not Found '.$e], 404);
        }
    }
    
    public function changePushTime($id, $pushed_at)
    {
        $push = $this->push_notif_repository->findById($id);
        $delete = $this->push_notif_repository->delete($push);
        if (!$delete) {
            return false;
        }
        $data['object_id'] = $push->object_id;
        $data['type'] = $push->type;
        $data['filter'] = $push->filter;
        $data['target'] = $push->target;
        $data['image'] = $push->image;
        $data['title'] = $push->title;
        $data['pushed_at'] = $pushed_at;

        $create = $this->push_notif_repository->create($data);
        if (!$create) {
            return false;
        }

        return true;
    }
    public function destroy($id)
    {
        try {
            $push_notif = $this->push_notif_repository->findById($id);
            $delete = $this->push_notif_repository->delete($push_notif);
            if ($delete) {
                return response()->json(['success' => 'Push Notification' . $push_notif->title . ' has been deleted'], 200);
            }
            return response()->json(['error' => 'Push Notification error to delete'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Push Notification Not Found'], 404);
        }
    }
}
