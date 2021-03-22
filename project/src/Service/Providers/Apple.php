<?php

namespace Push\Service\Providers;

use App\Model\Transaction;
use Envms\FluentPDO\Query;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use Propel\Runtime\Propel;
use Push\Model\Map\PushTokenTableMap;
use Stash\Pool;

class Apple extends Layout implements Provider
{
    /** @var string */
    protected $bundle_id;

    public function __construct(array $config)
    {
        parent::__construct($config);

        $this->bundle_id = $config['bundle_id'];
    }

    public function generateToken()
    {

    }

    public function send(array $tokens, array $push)
    {
        $data = [
            'aps' => [
                'alert' => [
                    'title' => $push['title'],
                    'subtitle'  => $push['subtitle'],
                    'body'  => $push['text'],
                ],
                'sound' => $push['sound'] ?? 'default'
            ],
            'payload' => $push['payload']
        ];

        try {
            foreach ($tokens as $token) {
                (new Client())->post($this->getUrl() . $token, [
                    'verify' => false,
                    'version' => 2.0,
                    'json' => $data,
                    'cert' => [$this->getFileDir()],
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'apns-push-type' => 'alert',
                        'apns-topic' => $this->bundle_id
                    ],
                ]);
            }
        }catch (\Throwable $e){
        }
    }
}
