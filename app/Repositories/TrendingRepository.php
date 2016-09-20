<?php
namespace App\Repositories;

use Kurio\Common\Models\TrendingKeyword;

class TrendingRepository
{

    public function getList()
    {
        $query = TrendingKeyword::orderBy('position', 'asc');

        return $query->get();
    }

    public function findById($id)
    {
        return TrendingKeyword::findOrFail($id);
    }

    public function create(array $data)
    {
        $trending = new TrendingKeyword;

        $trending->title = $data['title'];
        $trending->keywords = $data['keywords'];
        $trending->start_date = $data['start_date'];
        $trending->end_date = $data['end_date'];
        $trending->position = $data['position'];

        if (isset($data['use_twitter'])) {
            $trending->use_twitter = 1;
            $trending->twitter_whitelist = $data['twitter_whitelist'];
        }

        if (isset($data['image'])) {
            $trending->image = $data['image'];
        }

        if (!$trending->save()) {
            return false;

        }
        return $trending;

    }

    public function update($model, array $data)
    {
        $model->title = $data['title'];
        $model->keywords = $data['keywords'];
        $model->start_date = $data['start_date'];
        $model->end_date = $data['end_date'];

        if (isset($data['use_twitter'])) {
            if ($data['use_twitter'] == 0) {
                $model->use_twitter = 0;
            } else {
                $model->use_twitter = 1;
                if (isset($data['twitter_whitelist'])) {
                    $model->twitter_whitelist = $data['twitter_whitelist'];
                }
            }
        }

        if (isset($data['image'])) {
            $model->image = $data['image'];
        }

        if (!$model->save()) {
            return false;
        }
        return $model;

    }

    public function delete($model)
    {
        return $model->delete();
    }

    public function updatePosition($id, $position)
    {
        return TrendingKeyword::where('id', $id)->update(['position' => $position]);
    }

}