<?php

return [
    'propel' => [
        'database' => [
            'connections' => [
                'push' => [
                    'adapter' => 'pgsql',
                    'dsn' => 'pgsql:host=host.docker.internal;port=5432;dbname=push',
                    'user' => 'postgres',
                    'password' => 'root',
                    'settings' => [
                        'charset' => 'utf8',
                        'queries' => [
                            'utf8' => "SET NAMES 'UTF8'"
                        ]
                    ],
                    'attributes' => []
                ]
            ]
        ],
        'runtime' => [
            'defaultConnection' => 'push',
            'connections' => ['push']
        ],
        'generator' => [
            'defaultConnection' => 'push',
            'connections' => ['push']
        ]
    ]
];