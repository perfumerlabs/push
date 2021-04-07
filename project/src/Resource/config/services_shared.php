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

    'facade.token' => [
        'shared' => true,
        'class' => 'Push\\Facade\\TokenFacade',
        'arguments' => ['#domain.token', '#repository.token', '#providers.google', '#providers.huawei', '#providers.apple']
    ],

    'providers.google' => [
        'shared' => true,
        'class' => 'Push\\Service\\Providers\\Google',
        'arguments' => ['*google']
    ],

    'providers.web' => [
        'shared' => true,
        'class' => 'Push\\Service\\Providers\\Web',
        'arguments' => ['*google']
    ],

    'providers.apple' => [
        'shared' => true,
        'class' => 'Push\\Service\\Providers\\Apple',
        'arguments' => ['*apple']
    ],

    'providers.huawei' => [
        'shared' => true,
        'class' => 'Push\\Service\\Providers\\Huawei',
        'arguments' => ['*huawei']
    ],
];