<?php

return [
    'propel' => [
        'database' => [
            'connections' => [
                'push' => [
                    'adapter' => 'pgsql',
                    'dsn' => 'pgsql:host=db;port=5432;dbname=microservices',
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