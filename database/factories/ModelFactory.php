<?php

use Kurio\Common\Models\PushNotification;
use Kurio\Common\Models\TrendingKeyword;
use Kurio\Common\Models\Article;
use App\Models\AccessToken;

$factory->define(Article::class, function (Faker\Generator $faker) {
    return [
        'id' => 1,
        'url' => 'http://merdeka.com/travel/yuk-cek-tiket-japan-jam-beach-fest-2016.html',
        'title' => $faker->name,
        'content' => $faker->paragraphs,
        'json' => $faker->paragraphs, // to be replaced with valid Article JSON value
        'excerpt' => $faker->sentence,
        'thumbnail' => $faker->imageUrl(),
        'thumbnail_dimension' => '640x480',
        'updated_at' => $faker->date(),
        'curated' => 0,
        'curated_by' => null,
        'curated_at' => null,
        'deleted_at' => null,
        'pinned' => 0,
        'pinned_until' => $faker->date(),
        'published_at' => $faker->date()
    ];
});

$factory->define(PushNotification::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->randomNumber(5),
        'object_id' => 1,
        'title' => $faker->name,
        'created_at' => $faker->date(),
        'updated_at' => $faker->date(),
        'type' => $faker->sentence,
        'pushed_at' => $faker->date(),
        'filter' => $faker->sentence,
        'target' => $faker->sentence,
        'image' => $faker->sentence
    ];
});

$factory->define(TrendingKeyword::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->randomNumber(5),
        'title' => $faker->name,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
        'start_date' => date('Y-m-d H:i:s'),
        'end_date' => date('Y-m-d H:i:s'),
        'use_twitter' => 1,
        'keywords' => $faker->sentence,
        'twitter_whitelist' => $faker->sentence,
        'position' => 1,
        'image' => $faker->sentence
    ];
});

$factory->define(AccessToken::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->randomNumber(),
        'admin_id' => 1,
        'access_token' => date('Y-m-d H:i:s'),
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];
});