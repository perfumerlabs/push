<?php

namespace Project;

use Perfumer\Framework\Gateway\CompositeGateway;

class Gateway extends CompositeGateway
{
    protected function configure(): void
    {
        $this->addModule('push', null,   null, 'http');
        $this->addModule('push', 'push', null, 'cli');
    }
}