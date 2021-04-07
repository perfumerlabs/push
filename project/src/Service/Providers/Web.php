<?php

namespace Push\Service\Providers;

use Envms\FluentPDO\Query;
use Google\Client;
use GuzzleHttp\Client as Guzzle;
use Propel\Runtime\Propel;
use Push\Model\Map\PushTokenTableMap;

class Web extends Google
{
}
