<?php

namespace Push\Service;

class Queue
{
    private string $host;

    public function __construct($host)
    {
        $this->host = $host;
    }

    public function pushQueue($data, $queue_worker)
    {
        try {
            $result = (new \GuzzleHttp\Client())->post($this->host . '/task',
                [
                    'connect_timeout' => 5,
                    'json' => [
                        'worker' => $queue_worker,
                        'url' => $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/queue/send',
                        'method' => 'post',
                        'json' => $data
                    ]
                ]);

            if(in_array($result->getStatusCode(), [200, 201])){
                return true;
            }else{
                return false;
            }
        }catch (\Throwable $e){
            return false;
        }
    }
}