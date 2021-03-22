<?php

namespace Push\Module;

use Perfumer\Framework\Controller\Module;

class ControllerModule extends Module
{
    public $name = 'push';

    public $router = 'push.router';

    public $request = 'push.request';

    public $components = [
        'view' => 'view.status'
    ];
}