<?php

namespace Push\Module;

use Perfumer\Framework\Controller\Module;

class CommandModule extends Module
{
    public $name = 'push';

    public $router = 'router.console';

    public $request = 'push.request';
}