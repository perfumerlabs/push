<?php

return [
    'fast_router' => [
        'shared' => true,
        'init' => function(\Perfumer\Component\Container\Container $container) {
            return \FastRoute\simpleDispatcher(function(\FastRoute\RouteCollector $r) {
                $r->addRoute('POST', '/token', 'token.post');
                $r->addRoute('DELETE', '/token', 'token.delete');
                $r->addRoute('POST', '/send', 'send.post');
            });
        }
    ],

    'push.router' => [
        'shared' => true,
        'class' => 'Perfumer\\Framework\\Router\\Http\\FastRouteRouter',
        'arguments' => ['#gateway.http', '#fast_router', [
            'data_type' => 'json',
            'allowed_actions' => ['post', 'delete'],
        ]]
    ],

    'push.request' => [
        'class' => 'Perfumer\\Framework\\Proxy\\Request',
        'arguments' => ['$0', '$1', '$2', '$3', [
            'prefix' => 'Push\\Controller',
            'suffix' => 'Controller'
        ]]
    ],
];
