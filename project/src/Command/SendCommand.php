<?php

namespace Push\Command;

use Amp\Promise;
use function Amp\ParallelFunctions\parallelMap;
use Perfumer\Framework\Controller\PlainController;
use Perfumer\Framework\Router\ConsoleRouterControllerHelpers;

class SendCommand extends PlainController
{
    use ConsoleRouterControllerHelpers;

    public function action()
    {

    }
}
