<?php

Route::get('/', function () {
    return view('welcome');
});

/*route for login*/
Route::post('auth/login', ['uses' => 'Auth\AuthController@login']);                 //oke

/*route for topic*/
Route::get('topics', ['uses' => 'TopicController@getList']);                        //oke
Route::put('topic/{id}', ['uses' => 'TopicController@update']);                     //oke
Route::delete('/topic/{id}', ['uses' => 'TopicController@destroy']);                //oke
Route::post('/topic', ['uses' => 'TopicController@create']);                        //oke

/*route for article*/
Route::get('articles', ['uses' => 'ArticleController@getList']);                    //oke
Route::get('/article/{id}', ['uses' => 'ArticleController@view']);                  //oke
Route::put('/article/{id}', ['uses' => 'ArticleController@update']);                //oke
Route::post('/article/{id}/pin', ['uses' => 'ArticleController@pin']);              //oke
Route::delete('/article/{id}/pin', ['uses' => 'ArticleController@deletePin']);      //oke
Route::delete('/article/{id}', ['uses' => 'ArticleController@destroy']);            //oke
Route::post('/article/postSubmit', ['uses' => 'ArticleController@postSubmit']);     //oke

Route::get('/article/top_stories/pinned', ['uses' => 'ArticleController@getTopStoriesPinnedArticle']);
Route::get('/article/topic:{topic_id}/pinned', ['uses' => 'ArticleController@getPinnedArticleByTopic']);

/*route for trending*/
Route::get('trending', ['uses' => 'TrendingController@getList']);                   //oke
Route::post('/trending', ['uses' => 'TrendingController@create']);                  //oke
Route::put('/trending/{id}', ['uses' => 'TrendingController@update']);              //oke
Route::delete('/trending/{id}', ['uses' => 'TrendingController@destroy']);          //oke
Route::patch('/trending', ['uses' => 'TrendingController@changePosition']);         //oke

/*route for push notification*/
Route::get('push_notif', ['uses' => 'PushNotifController@getList']);                //oke
Route::post('/push_notif', ['uses' => 'PushNotifController@create']);               //oke
Route::put('/push_notif/{id}', ['uses' => 'PushNotifController@update']);           //oke
Route::delete('/push_notif/{id}', ['uses' => 'PushNotifController@destroy']);       //oke