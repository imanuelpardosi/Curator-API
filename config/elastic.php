<?php
return [
    'hosts' => env('ELASTICSEARCH_HOSTS', '127.0.0.1'),
    'index' => env('ELASTICSEARCH_INDEX', 'kurio_engine'),
    'trending_min_score' => 0,
];
