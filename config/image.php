<?php
return [
    'host' => env('IMAGE_HOST'),

    'storage' => env('IMAGE_STORAGE'),

    'local' => [
        'path' => env('IMAGE_LOCAL_PATH')
    ],

    's3' => [
        'key' => env('IMAGE_S3_KEY'),
        'secret' => env('IMAGE_S3_SECRET'),
        'region' => env('IMAGE_S3_REGION'),
        'bucket' => env('IMAGE_S3_BUCKET'),
    ],

    'min_width' => null,
    'min_height' => null,

    'max_width' => null,
    'max_height' => null,

    'min_ratio' => null,
    'max_ratio' => null
];
