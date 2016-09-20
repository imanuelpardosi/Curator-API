<?php
namespace App\Repositories;

use Kurio\Common\Events\NewArticleSaved;
use Kurio\Common\Events\ArticleUpdated;
use Illuminate\Database\Eloquent\Model;
use Kurio\Common\Models\Article;

class ApiArticleRepository
{
    public function findById($id)
    {
        $query = Article::findOrFail($id);
        return $query;
    }

    public function update($model, array $data)
    {
        $model->title = $data['title'];

        if (!empty($data['excerpt'])) {
            $model->excerpt = $data['excerpt'];
        }

        if (!empty($data['curated_by'])) {
            $model->curated = 1;
            $model->curated_at = date('Y-m-d H:i:s');
            $model->curated_by = $data['curated_by'];
        }

        if (!empty($data['pinned_until'])) {
            $model->pinned = 1;
            $model->pinned_until = $data['pinned_until'];
        }

        if (isset($data['thumbnail'])) {
            $model->thumbnail = $data['thumbnail'];
            $model->thumbnail_dimension = $this->getThumbnailDimension($data['thumbnail']);
        }

        if ($model->save()) {
            if (!empty($data['topics'])) {
                $model->topics()->sync($data['topics']);
            }
            event(new ArticleUpdated($model));
            return $model;
        }
        return false;
    }

    public function delete($model)
    {
        return $model->delete();
    }

    public function pin(Model $model, $data)
    {
        $model->pinned = $data['pinned'];
        $model->pinned_until = $data['pinned_until'];
        $model->curated = 1;
        $model->curated_at = date('Y-m-d H:i:s');
        $model->curated_by = $data['curated_by'];

        if ($model->save()) {
            return $model;

        }
        return false;

    }

    public function create(array $data)
    {
        $article = new Article;

        $article->url = $data['url'];
        $article->title = $data['title'];
        $article->content = $data['content'];
        $article->json = $data['json'];
        $article->excerpt = $data['excerpt'];
        $article->thumbnail = $data['thumbnail'];

        $thumbnail_dimension = !empty($data['thumbnail']) ? $this->getThumbnailDimension($data['thumbnail']) : '';
        $article->thumbnail_dimension = $thumbnail_dimension;

        if (!empty($data['published_at']) and is_numeric($data['published_at'])) {
            $article->published_at = date('Y-m-d H:i:s', $data['published_at']);
        } else {
            $article->published_at = date('Y-m-d H:i:s');
        }

        if (!empty($data['curated_by'])) {
            $article->curated = 1;
            $article->curated_at = date('Y-m-d H:i:s');
            $article->curated_by = $data['curated_by'];
        }

        if (!empty($data['pinned_until'])) {
            $article->pinned = 1;
            $article->pinned_until = $data['pinned_until'];
        }

        if (!$article->save()) {
            return false;

        }
        if (!empty($data['topics'])) {
            $article->topics()->sync($data['topics']);
        }
        if (!empty($data['sources'])) {
            $article->sources()->sync([$data['sources']]);
        }
        event(new NewArticleSaved($article));
        return $article;

    }

    protected function getThumbnailDimension($url)
    {
        try {
            list($w, $h) = getimagesize($url);
            return $w . 'x' . $h;

        } catch (\Exception $e) {
            return null;

        }
    }

    public function getTopicByArticleId($article_id)
    {
        $article = $this->findById($article_id);
        $topic = $article->topics->first();
        return $topic;
    }

    public function getByUrl($url, $with_trashed = false)
    {
        return $this->findByUrl($url, $with_trashed)->first();
    }

    public function findByUrl($url, $with_trashed = false)
    {
        $query = Article::where('url', '=', $url);

        if ($with_trashed) {
            $query->withTrashed();
        }
        return $query;
    }

    public function getSourceByArticleId($article_id)
    {
        $article = $this->findById($article_id);
        $source= $article->sources->first();
        return $source;
    }

    public function updatePinnedStatus(Model $model, $status)
    {
        $model->pinned = $status;

        if (!$model->save()) {
            return false;
        }
        return $model;
    }
}