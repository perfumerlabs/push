<?php

namespace Push\Service\Providers;

use Envms\FluentPDO\Query;
use Propel\Runtime\Propel;
use Push\Model\Map\PushTokenTableMap;

class Layout
{
    private $config_file;

    private $url;

    public $chunk_size;

    public function __construct(array $config, $chunk_size)
    {
        $this->config_file = $config['file'];
        $this->url = $config['url'];
        $this->chunk_size = $chunk_size;
    }

    public function getFileDir()
    {
        return '/opt/config/' . $this->getFileName();
    }

    public function getFileName()
    {
        return $this->config_file;
    }

    public function getUrl()
    {
        return $this->url;
    }
}
