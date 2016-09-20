<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Kurio\Common\Models\PushNotification;
use Mockery as m;

class PushNotifControllerTest extends TestCase
{
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();
        $this->pushNotif = factory(PushNotification::class);
        $this->repo_pushNotif = app()->make('App\Repositories\PushNotifRepository');
    }

    public function testCreate()
    {
        $model = $this->pushNotif->make();

        $expected = [
            'id' => 1,
            'object_id' => $model->object_id,
            'title' => $model->title,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'type' => $model->type,
            'pushed_at' => $model->pushed_at,
            'filter' => $model->filter,
            'target' => $model->target,
            'image' => $model->image
        ];

        $return = $this->repo_pushNotif->create($model->toArray());
        $this->assertInstanceOf(Illuminate\Database\Eloquent\Model::class, $model);
        $this->assertEquals($expected, $return->toArray());
    }

    public function testUpdate()
    {
        $model = $this->pushNotif->make();
        $model->save();

        $expected = [
            'id' => $model->id,
            'object_id' => $model->object_id,
            'title' => 'update value of title',
            'created_at' => $model->created_at,
            'updated_at' => date('Y-m-d H:i:s'),
            'type' => $model->type,
            'pushed_at' => $model->pushed_at,
            'filter' => $model->filter,
            'target' => $model->target,
            'image' => $model->image
        ];
        $title['title'] = 'update value of title';
        $model_pushNotif = $this->repo_pushNotif->findById($model->id);
        $return = $this->repo_pushNotif->update($model_pushNotif, $title);
        $this->assertInstanceOf(Illuminate\Database\Eloquent\Model::class, $model);
        $this->assertEquals($expected, $return->toArray());
    }

    public function testGetList()
    {
        $list_push = factory(PushNotification::class, 12)->create()->each(function ($u) {
            $u->save();
        });

        $return = $this->repo_pushNotif->getList(10);

        $this->assertEquals($return->count(), 10);
        $this->assertEquals($list_push->count(), 12);
    }

    public function testDelete()
    {
        $model = $this->pushNotif->make();
        $model->save();
        $model_pushNotif = $this->repo_pushNotif->findById($model->id);
        $return = $this->repo_pushNotif->delete($model_pushNotif);
        $this->assertInstanceOf(Illuminate\Database\Eloquent\Model::class, $model);
        $this->assertEquals(true, $return);
    }

}