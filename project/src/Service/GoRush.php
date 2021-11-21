<?php

namespace Push\Service;

use GuzzleHttp\Client;
use Project\Model\GoRush\Notifications;
use Throwable;

class GoRush
{
    const PLATFORM_APPLE = 1;
    const PLATFORM_GOOGLE = 2;
    const PLATFORM_HUAWEI = 3;

    private string $host;

    public function __construct($host)
    {
        $this->host = $host;
    }

    public function send(Notifications $notifications): bool
    {
        try {
            $result = (new Client())->post($this->host . '/api/push',
                [
                    'connect_timeout' => 5,
                    'json' => $notifications->toArray()
                ]);

            if(in_array($result->getStatusCode(), [200, 201])){
                return true;
            }else{
                return false;
            }
        }catch (Throwable $e){
            return false;
        }
    }
}