<?php

return [
    'key' => env('EVENTSTORE_KEY', null),
    'tcp_url' => env('EVENTSTORE_TCP_URL', 'tls://admin:changeit@localhost:1113'),
    'http_url' => env('EVENTSTORE_HTTP_URL', 'http://admin:changeit@localhost:2113'),
    'streams' => [],
    'group' => '',
    'namespace' => 'App\Events'
];
