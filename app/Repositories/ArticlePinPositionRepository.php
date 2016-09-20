<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Models\ArticlePinPosition;
use DB;

class ArticlePinPositionRepository
{
    public function findById($id)
    {
        $query = ArticlePinPosition::findOrFail($id);
        return $query;
    }

    public function findByArticleId($id)
    {
        $query = ArticlePinPosition::where('id_article','=',$id)->first();
        return $query;
    }
    
    public function findTopStoriesPinnedArticle($cursor, $size)
    {
        $query = ArticlePinPosition::orderBy('position', 'asc')->where('is_top_story','=',1)->skip($cursor)->take($size)->get();
        return $query;
    }

    public function findPinnedArticleByTopic($topic_id, $cursor, $size)
    {
        $query = ArticlePinPosition::orderBy('position', 'asc')->where('id_topic','=',$topic_id)->skip($cursor)->take($size)->get();
        return $query;
    }

    public function deletePin($id)
    {
        $query = ArticlePinPosition::where('id_article','=',$id)->first();

        if ($query->delete()) {
            return true;
        }
        return false;
    }

    public function pin(Model $model, $data)
    {
        $model->id_topic = $data['id_topic'];
        $model->is_top_story = 0;
        $model->position = $data['position'];
        $model->start_date = $data['start_date'];
        $model->end_date = $data['end_date'];
        $model->updated_at = date('Y-m-d H:i:s');
        $model->deleted_at = $data['deleted_at'];

        if ($model->save()) {
            return $model;
        }
        return false;
    }

    public function addPin($data)
    {
        $model = new ArticlePinPosition();

        $model->id_article = $data['id_article'];
        $model->id_topic = $data['id_topic'];
        $model->is_top_story = 0;
        $model->position = $data['position'];
        $model->start_date = $data['start_date'];
        $model->end_date = $data['end_date'];
        $model->updated_at = date('Y-m-d H:i:s');
        $model->deleted_at = $data['deleted_at'];

        if ($model->save()) {
            return $model;
        }
        return false;
    }

    public function addPinTopStory($data)
    {
        $model = new ArticlePinPosition();

        $model->id_article = $data['id_article'];
        $model->id_topic = null;
        $model->is_top_story = 1;
        $model->position = $data['position'];
        $model->start_date = $data['start_date'];
        $model->end_date = $data['end_date'];
        $model->updated_at = date('Y-m-d H:i:s');
        $model->deleted_at = $data['deleted_at'];

        if ($model->save()) {
            return $model;
        }
        return false;
    }

    public function pinTopStory(Model $model, $data)
    {
        $model->id_topic = null;
        $model->is_top_story = 1;
        $model->position = $data['position'];
        $model->start_date = $data['start_date'];
        $model->end_date = $data['end_date'];
        $model->updated_at = date('Y-m-d H:i:s');
        $model->deleted_at = $data['deleted_at'];

        if ($model->save()) {
            return $model;
        }
        return false;
    }


    public function isExistsTopic($id_topic, $position, $start_date, $end_date)
    {
        $query = ArticlePinPosition::
            where('id_topic', $id_topic)
            ->where('position', $position)
            ->where('start_date', '<', $start_date)
            ->where('start_date', '<', $end_date)
            ->where('end_date', '>', $start_date)
            ->where('end_date', '>', $end_date)
            ->exists();

        if ($query) {
            return true;
        }
        return false;
    }

    public function isExistsTopStory($position, $start_date, $end_date)
    {
        $query = ArticlePinPosition::
        where('is_top_story', 1)
            ->where('position', $position)
            ->where('start_date', '<', $start_date)
            ->where('start_date', '<', $end_date)
            ->where('end_date', '>', $start_date)
            ->where('end_date', '>', $end_date)
            ->exists();

        if ($query) {
            return true;
        }
        return false;
    }



    /*
     *
     *
     *

    public function getListByTopic($id_topic)
    {
        $query = ArticlePinPosition::orderBy('position', 'asc')->where('id_topic', '=', $id_topic);
        return $query->get();
    }

    public function getListTopStory()
    {
        $query = ArticlePinPosition::orderBy('position', 'asc')->where('is_top_story', '=', 1);
        return $query->get();
    }

    public function addPositionTopic($position, $id_topic)
    {
        $query = ArticlePinPosition::where('position', '>', $position)->first();
        if (!empty($query)) {
            $query = DB::table('article_pin_position')->where('position', '>=', $position)->where('id_topic', '=', $id_topic)->increment('position');
            return $query;
        } elseif (empty($query)) {
            return true;
        }
        return false;
    }

    public function addPositionTopStory($position)
    {
        $query = ArticlePinPosition::where('position', '>', $position)->first();
        if (!empty($query)) {
            $query = DB::table('article_pin_position')->where('position', '>=', $position)->where('is_top_story', '=', 1)->increment('position');
            return $query;
        } elseif (empty($query)) {
            return true;
        }
        return false;
    }

    public function changePositionTopic($current_pos, $to_pos, $id_topic)
    {
        if ($current_pos > $to_pos) {
            $query = DB::table('article_pin_position')->where('position', '>=', $to_pos)->where('position', '<=', $current_pos)->where('id_topic', '=', $id_topic)->increment('position');
        } elseif ($current_pos < $to_pos) {
            $query = DB::table('article_pin_position')->where('position', '>=', $current_pos)->where('position', '<=', $to_pos)->where('id_topic', '=', $id_topic)->decrement('position');
        } else {
            $query = false;
        }
        return $query;
    }

    public function changePositionTopStory($current_pos, $to_pos)
    {
        if ($current_pos > $to_pos) {
            $query = DB::table('article_pin_position')->where('position', '>=', $to_pos)->where('position', '<=', $current_pos)->where('is_top_story', '=', 1)->increment('position');
        } elseif ($current_pos < $to_pos) {
            $query = DB::table('article_pin_position')->where('position', '>=', $current_pos)->where('position', '<=', $to_pos)->where('is_top_story', '=', 1)->decrement('position');
        } else {
            $query = false;
        }
        return $query;
    }

    public function updatePosition($id_topic)
    {
        $data = $this->getListByTopic($id_topic);
        $pos = 1;
        foreach ($data as $key) {
            $id = $key->id;
            ArticlePinPosition::where('id', $id)->update(['position' => $pos]);
            $pos++;
        }

        $data = $this->getListTopStory();
        $pos = 1;
        foreach ($data as $key) {
            $id = $key->id;
            ArticlePinPosition::where('id', $id)->update(['position' => $pos]);
            $pos++;
        }
    }
    */

}