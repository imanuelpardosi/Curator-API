<?php

use Mockery as m;
use App\Models\AccessToken;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class AccessTokenRepositoryTest extends TestCase
{
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();
        $this->accessToken = factory(AccessToken::class);
        $this->repo_AccessToken = app()->make('App\Repositories\AccessTokenRepository');
    }

    public function testFindToken()
    {
        $model = $this->accessToken->make();
        $model->save();
        $model_accessToken = $this->repo_AccessToken->findToken($model->id);
        $this->assertInstanceOf(Illuminate\Database\Eloquent\Model::class, $model);
        $this->assertNotNull(true, $model_accessToken);
    }

}