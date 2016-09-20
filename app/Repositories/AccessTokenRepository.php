<?php
namespace App\Repositories;

use App\Models\AccessToken;

class AccessTokenRepository
{
    public function findToken($keyword)
    {
        $query = AccessToken::where('access_token', $keyword);
        return $query->first();
    }

    public function generateToken($token)
    {
        $model = new AccessToken();
        $model->admin_id = \Auth::user()->id;
        $model->access_token = $token;

        if ($model->save()) {
            return $model;
        }
        return false;

    }

}