<?php

return [
    'propel' => [
        'bin'           => 'vendor/bin/propel',
        'project'       => 'push',
        'database'      => 'pgsql',
        'dsn'           => 'pgsql:host=db;port=5432;dbname=microservices',
        'db_user'       => 'postgres',
        'db_password'   => 'root',
        'platform'      => 'pgsql',
        'config_dir'    => 'src/Resource/propel/connection',
        'schema_dir'    => 'src/Resource/propel/schema',
        'model_dir'     => 'src/Model',
        'migration_dir' => 'src/Resource/propel/migration',
        'migration_table' => 'push_propel_migration',
    ],

    'push' => [
        'timezone' => 'Utc',
    ],

    'google' => [
        'file' => 'google_prod.json',
        'url'  => 'https://fcm.googleapis.com/v1/projects/myproject-b5ae1/messages:send',
    ],
    'apple' => [
        'file' => 'prod1.pem',
        'url'  => 'https://api.push.apple.com:443/3/device/',
        'bundle_id' => 'kz.naimi.app',
    ],
    'huawei' => [
        'file' => 'huawei_prod.json',
        'url'  => 'https://push-api.cloud.huawei.com/v1/%s/messages:send',
    ],
];