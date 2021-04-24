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
                    'body'  => $push['text'],
                ],
                'sound' => array_key_exists('sound', $push) ? ($push['sound'] . '.wav') : 'default'
            ],
            'payload' => $push['payload']
        ];

        if($push['subtitle'] ?? null){
            $data['aps']['alert']['subtitle'] = $push['subtitle'];
        }

        $delete = [];

        foreach ($tokens as $token) {
            $user_key = $token['user_key'];
            $token = $token['token'];
            try {
                (new Client())->post($this->getUrl() . $token, [
                    'verify' => false,
                    'version' => 2.0,
                    'json' => $data,
                    'cert' => [$this->getFileDir(), null],
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'apns-push-type' => 'alert',
                        'apns-topic' => $this->bundle_id
                    ],
                ]);

            } catch (\Throwable $e) {
                if(in_array($e->getCode(), [400, 410])){
                    $delete[] = $user_key;
                }
                error_log("APPLE $token " . $e->getMessage());
            }
        }

        return $delete;
    }
}
