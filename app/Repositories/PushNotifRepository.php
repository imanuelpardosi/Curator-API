<?php

namespace App\Repositories;

use Kurio\Common\Models\PushNotification;

class PushNotifRepository
{

    public function getList($num = null)
    {
        $query = PushNotification::orderBy('pushed_at', 'desc');

        return !empty($num) ? $query->simplePaginate($num) : $query->get();
    }

    public function findById($id)
    {
        $query =  PushNotification::findOrFail($id);

        return $query;
    }

    public function create(array $data)
    {
        $push = new PushNotification;

        $push->type = $data['type'];
        $push->title = $data['title'];
        $push->object_id = $data['object_id'];

        if (isset($data['filter'])) {
            $push->filter = $data['filter'];
        }
        if (empty($data['pushed_at'])) {
            $data['pushed_at'] = date('Y-m-d H:i:s');
        }

        $push->target = $data['target'];
        $push->pushed_at = $data['pushed_at'];
        $push->image = $data['image'];

        return $push->save() ? $push : false;
    }

    public function update($model, array $data)
    {
        $model->title = $data['title'];

        $model->save();

        return $model;
    }

    public function delete($model)
    {
        return $model->delete();
    }

}
