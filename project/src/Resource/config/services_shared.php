<?php

return [
    'gateway' => [
        'shared' => true,
        'class' => 'Project\\Gateway',
        'arguments' => ['#application', '#gateway.http', '#gateway.console']
    ],

    'repository.token' => [
        'shared' => true,
        'class' => 'Push\\Repository\\TokenRepository',
    ],

    'domain.token' => [
        'shared' => true,
        'class' => 'Push\\Domain\\TokenDomain',
        'arguments' => ['#repository.token']
    ],

    'domain.log' => [
        'shared' => true,
        'class' => 'Push\\Domain\\LogDomain'
    ],

    'facade.token' => [
        'shared' => true,
        'class' => 'Push\\Facade\\TokenFacade',
        'arguments' => ['#domain.token', '#domain.log', '#repository.token', '#providers.google', '#providers.huawei', '#providers.apple']
    ],

    'providers.google' => [
        'shared' => true,
        'class' => 'Push\\Service\\Providers\\Google',
        'arguments' => ['*google', '@push/chunk_size']
    ],

    'providers.web' => [
        'shared' => true,
        'class' => 'Push\\Service\\Providers\\Web',
        'arguments' => ['*google', '@push/chunk_size']
    ],

    'providers.apple' => [
        'shared' => true,
        'class' => 'Push\\Service\\Providers\\Apple',
        'arguments' => ['*apple', '@push/chunk_size']
    ],

    'providers.huawei' => [
        'shared' => true,
        'class' => 'Push\\Service\\Providers\\Huawei',
        'arguments' => ['*huawei', '@push/chunk_size']
    ],
];