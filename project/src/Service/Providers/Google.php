<?php

namespace Push\Service\Providers;

use Envms\FluentPDO\Query;
use Google\Client;
use GuzzleHttp\Client as Guzzle;
use Propel\Runtime\Propel;
use Push\Model\Map\PushTokenTableMap;

class Google extends Layout implements Provider
{
    /** @var array */
    protected $json_config;

    public function __construct(array $config)
    {
        parent::__construct($config);

        $this->json_config = json_decode(file_get_contents($this->getFileDir()), true);

        putenv("GOOGLE_APPLICATION_CREDENTIALS=" . $this->getFileDir());
    }

    public function generateToken()
    {

    }

    public function getClient()
    {
        $client = new Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(\Google_Service_FirebaseCloudMessaging::CLOUD_PLATFORM);

        return $client;
    }

    public function send(array $tokens, array $push)
    {
        $image = $push['image'] ?? null;
        $title = $push['title'] ?? null;
        $text = $push['text'] ?? null;

        $payload = [
            'image' => $image,
            'title' => $title,
            'message' => $text,
        ];

        if($push['payload'] ?? false){
            foreach ($push['payload'] as $key => $item){
                $payload[$key] = !is_string($item) ? (is_array($item) ? json_encode($item) : (string)$item) : $item;
            }
        }

        $client = new \Google_Service_FirebaseCloudMessaging($this->getClient());

        $android = new \Google_Service_FirebaseCloudMessaging_AndroidConfig();
        $android->setData($payload);

        $message = new \Google_Service_FirebaseCloudMessaging_Message();
        $message->setData($payload);
        $message->setAndroid($android);

        $delete = [];

        foreach ($tokens as $token){
            $user_key = $token['user_key'];
            $token = $token['token'];

            try {
                $message->setToken($token);

                $send_body = new \Google_Service_FirebaseCloudMessaging_SendMessageRequest();
                $send_body->setMessage($message);

                $client->projects_messages->send('projects/' . $this->json_config['project_id'], $send_body);
            }catch (\Throwable $e){
                $error = json_decode($e->getMessage(), true);
                if(is_array($error)){
                    $error = $error['error'];
                    if($error['code'] === 404){
                        $delete[] = $user_key;
                    }
                    error_log("GOOGLE $token " . $error['message']);
                }
            }
        }

        return $delete;
    }
}
