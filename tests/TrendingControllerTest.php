<?php

use Mockery as m;
use Kurio\Common\Models\TrendingKeyword;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class TrendingControllerTest extends TestCase
{
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();
        $this->trending = factory(TrendingKeyword::class);
        $this->repo_trending = app()->make('App\Repositories\TrendingRepository');
    }

    public function testCreate()
    {
        $model = $this->trending->make();

        $expected = [
            'id' => 1,
            'title' => $model->title,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'start_date' => $model->start_date,
            'end_date' => $model->end_date,
            'use_twitter' => $model->use_twitter,
            'keywords' => $model->keywords,
            'twitter_whitelist' => $model->twitter_whitelist,
            'position' => $model->position,
            'image' => $model->image
        ];

        $return = $this->repo_trending->create($model->toArray());
        $this->assertInstanceOf(Illuminate\Database\Eloquent\Model::class, $model);
        $this->assertEquals($expected, $return->toArray());
    }

    public function testUpdate_UseTwitter_One_no_Twitter_Whitelist()
    {
        $model = $this->trending->make();
        $model->save();

        $expected = [
            'id' => $model->id,
            'title' => 'update value of title',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'start_date' => $model->start_date,
            'end_date' => $model->end_date,
            'use_twitter' => 1,
            'keywords' => 'update value of keywords',
            'twitter_whitelist' => $model->twitter_whitelist,
            'position' => $model->position,
            'image' => $model->image
        ];

        $case = [
            'title' => 'update value of title',
            'start_date' => date('Y-m-d H:i:s'),
            'end_date' => date('Y-m-d H:i:s'),
            'keywords' => 'update value of keywords',
            'use_twitter' => 1,
        ];

        $update['title'] = 'update value of title';
        $model_trending = $this->repo_trending->findById($model->id);
        $return = $this->repo_trending->update($model_trending, $case);
        $this->assertInstanceOf(Illuminate\Database\Eloquent\Model::class, $model);
        $this->assertEquals($expected, $return->toArray());
    }

    public function testUpdate_UseTwitter_One_with_Twitter_Whitelist()
    {
        $model = $this->trending->make();
        $model->save();

        $expected = [
            'id' => $model->id,
            'title' => 'update value of title',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'start_date' => $model->start_date,
            'end_date' => $model->end_date,
            'use_twitter' => 1,
            'keywords' => 'update value of keywords',
            'twitter_whitelist' => 'twitter white list',
            'position' => $model->position,
            'image' => $model->image
        ];

        $case = [
            'title' => 'update value of title',
            'start_date' => date('Y-m-d H:i:s'),
            'end_date' => date('Y-m-d H:i:s'),
            'keywords' => 'update value of keywords',
            'use_twitter' => 1,
            'twitter_whitelist' => 'twitter white list',
        ];

        $update['title'] = 'update value of title';
        $model_trending = $this->repo_trending->findById($model->id);
        $return = $this->repo_trending->update($model_trending, $case);
        $this->assertInstanceOf(Illuminate\Database\Eloquent\Model::class, $model);
        $this->assertEquals($expected, $return->toArray());
    }

    public function testUpdate_UseTwitter_Zero()
    {
        $model = $this->trending->make();
        $model->save();

        $expected = [
            'id' => $model->id,
            'title' => 'update value of title',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'start_date' => $model->start_date,
            'end_date' => date('Y-m-d H:i:s'),
            'use_twitter' => 0,
            'keywords' => 'update value of keywords',
            'twitter_whitelist' => $model->twitter_whitelist,
            'position' => $model->position,
            'image' => $model->image
        ];

        $case = [
            'title' => 'update value of title',
            'start_date' => date('Y-m-d H:i:s'),
            'end_date' => date('Y-m-d H:i:s'),
            'keywords' => 'update value of keywords',
            'use_twitter' => 0,
        ];

        $update['title'] = 'update value of title';
        $model_trending = $this->repo_trending->findById($model->id);
        $return = $this->repo_trending->update($model_trending, $case);
        $this->assertInstanceOf(Illuminate\Database\Eloquent\Model::class, $model);
        $this->assertEquals($expected, $return->toArray());
    }

    public function testUpdate_UseTwitter_Empty()
    {
        $model = $this->trending->make();
        $model->save();

        $expected = [
            'id' => $model->id,
            'title' => 'update value of title',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'start_date' => $model->start_date,
            'end_date' => $model->end_date,
            'use_twitter' => 1,
            'keywords' => 'update value of keywords',
            'twitter_whitelist' => $model->twitter_whitelist,
            'position' => $model->position,
            'image' => $model->image
        ];

        $case = [
            'title' => 'update value of title',
            'start_date' => date('Y-m-d H:i:s'),
            'end_date' => date('Y-m-d H:i:s'),
            'keywords' => 'update value of keywords',
        ];

        $update['title'] = 'update value of title';
        $model_trending = $this->repo_trending->findById($model->id);
        $return = $this->repo_trending->update($model_trending, $case);
        $this->assertInstanceOf(Illuminate\Database\Eloquent\Model::class, $model);
        $this->assertEquals($expected, $return->toArray());
    }

    public function testGetList()
    {
        $list_trending = factory(TrendingKeyword::class, 11)->create()->each(function ($u) {
            $u->save();
        });
        $return = $this->repo_trending->getList();
        $this->assertEquals($list_trending->count(), $return->count());
    }

    public function testDelete()
    {
        $model = $this->trending->make();
        $model->save();
        $model_trending = $this->repo_trending->findById($model->id);
        $return = $this->repo_trending->delete($model_trending);
        $this->assertInstanceOf(Illuminate\Database\Eloquent\Model::class, $model);
        $this->assertEquals(true, $return);
    }

}