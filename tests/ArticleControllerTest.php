<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Kurio\Common\Models\Article;
use Mockery as m;

class ArticleControllerTest extends TestCase
{
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();
        $this->article = factory(Article::class);
        $this->repo_article= app()->make('App\Repositories\ApiArticleRepository');
    }

    public function testCreate()
    {
        $model = $this->article->make();

        $data = [
            'url'=>'http://www.merdeka.com/travel/yuk-cek-tiket-japan-jam-beach-fest-2016.html',
            'title' => date('Y-m-d H:i:s'),
            'content' => 'content test value',
            'json' => '{}',
            'thumbnail' => '',
            'excerpt' => 'testing',
            'curated_at' => date('Y-m-d H:i:s'),
            'pinned_until' => date('Y-m-d H:i:s')
        ];

        $model->title = $data['title'];
        $model->content = $data['content'];
        $model->json = $data['json'];
        $model->excerpt = $data['excerpt'];
        $model->thumbnail = $data['thumbnail'];
        $model->curated_at = $data['curated_at'];
        $model->pinned_until = $data['pinned_until'];

        $return = $model->save();

        $expected = [
            'id' => $model->id,
            'url' => $model->url,
            'title' => $model->title,
            'content' => $model->content,
            'json' => $model->json,
            'excerpt' => $model->excerpt,
            'thumbnail' => $model->thumbnail,
            'curated_by' => $model->curated_by,
            'curated_at' => $model->curated_at,
            'pinned_until' => $model->pinned_until,
            'deleted_at' => $model->deleted_at,
            'thumbnail_dimension' => $model->thumbnail_dimension,
            'updated_at' => $model->updated_at,
            'curated' => $model->curated,
            'pinned' => $model->pinned,
            'created_at' => $model->created_at,
            'published_at' => $model->published_at

        ];

        $this->assertInstanceOf(Illuminate\Database\Eloquent\Model::class, $model);
        $this->assertTrue($return);
        $this->assertEquals($expected, $model->toArray());
    }

}