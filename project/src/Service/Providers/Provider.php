<?php

namespace Push\Service\Providers;

use Envms\FluentPDO\Query;
use Propel\Runtime\Propel;
use Push\Model\Map\PushTokenTableMap;

interface Provider
{
    public function send(array $tokens, array $push);
}
