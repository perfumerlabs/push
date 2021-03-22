<?php

return [
    'propel' => [
        'bin'           => 'vendor/bin/propel',
        'project'       => 'push',
        'database'      => 'pgsql',
        'dsn'           => 'pgsql:host=PG_HOST;port=PG_PORT;dbname=PG_DATABASE',
        'db_user'       => 'PG_USER',
        'db_password'   => 'PG_PASSWORD',
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
        'file' => 'GOOGLE_FILE',
        'url'  => 'GOOGLE_URL',
    ],
    'apple' => [
        'file' => 'APPLE_FILE',
        'url'  => 'APPLE_URL',
        'bundle_id' => 'APPLE_BUNDLE_ID',
    ],
    'huawei' => [
        'file' => 'HUAWEI_FILE',
        'url'  => 'HUAWEI_URL',
    ],
];