<?php

namespace Push\Service\Providers;

use Envms\FluentPDO\Query;
use GuzzleHttp\Client;

class Huawei extends Layout implements Provider
{
    /** @var array */
    protected $json_config;

    public function __construct(array $config, $chunk_size)
    {
        parent::__construct($config, $chunk_size);

        if ($this->getFileDir()) {
            $this->json_config = json_decode(file_get_contents($this->getFileDir()), true);
        }
    }

    public function getUrl()
    {
        return sprintf(parent::getUrl(), $this->json_config['app_id']);
    }

    public function generateToken()
    {

    }

    public function getAccessToken()
    {
        try {
            $response = (new Client())->post($this->json_config['token_uri'], [
                'form_params' => [
                    "grant_type"    => 'client_credentials',
                    "client_id"     => $this->json_config['client_id'],
                    "client_secret" => $this->json_config['client_secret'],
                ],
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ]
            ]);

            if($response->getStatusCode() < 204){
                return json_decode($response->getBody()->getContents(), true)['access_token'];
            }else{
                return false;
            }
        }catch (\Throwable $e){
            return false;
        }
    }

    public function send(array $tokens, array $push)
    {
        $data = array_merge($push['payload'], [
            'title' => $push['title'],
            'text' => $push['text'],
            'image' => $push['image'],
            'sound' => $push['sound'],
        ]);

        $data = [
            'validate_only' => false,
            'message' => [
                'data' => json_encode($data),
                //TODO Обработка пуша типа Data
//                'notification' => [
//                    'title' => $push['title'],
//                    'body'  => $push['text'],
//                    'image' => $push['image'] ?? null
//                ],
//                'android' => [
//                    'notification' => [
//                        'sound' => $push['sound'],
//                        'title' => $push['title'],
//                        'body'  => $push['text'],
//                        'image' => $push['image'] ?? null,
//                        "click_action" => [
//                            "type" => 1,
//                            "intent" => '#Intent;compo=com.rvr/.Activity;S.W=U;end'
//                        ]
//                    ]
//                ],
                'token' => array_column($tokens, 'token')
            ]
        ];

        try {
            $response = (new Client())->post($this->getUrl(), [
                'json' => $data,
                'headers' => [
                    'Content-Type' => 'application/json; charset=UTF-8',
                    'Authorization' => ['Bearer' => $this->getAccessToken()]
                ],
            ]);

            $response = json_decode($response->getBody()->getContents(), true);
            
            $delete = [];
            $code = $response['code'] ?? false;

            if($code) {
                if($code === '80300007'){
                    $delete = array_column($tokens, 'user_key');
                }elseif($code === '80100000') {
                    if ($response['msg'] ?? false) {
                        $errors = json_decode($response['msg'], true);
                        if ($errors['illegal_tokens'] ?? false) {
                            foreach ($tokens as $item) {
                                if (in_array($item['token'], $errors['illegal_tokens'])) {
                                    $delete[] = $item['user_key'];
                                }
                            }
                        }
                    }
                }
            }

            return $delete;
        }catch (\Throwable $e){
            error_log("HUAWEI " . $e->getMessage());
        }
    }
}
