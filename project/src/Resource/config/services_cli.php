<?php

return [
    'push.request' => [
        'class' => 'Perfumer\\Framework\\Proxy\\Request',
        'arguments' => ['$0', '$1', '$2', '$3', [
            'prefix' => 'Push\\Command',
            'suffix' => 'Command'
        ]]
    ]
];
