<?php

namespace Push\Service\Providers;

use Amp\MultiReasonException;
use Google\Client;
use function Amp\ParallelFunctions\parallelMap;

class Google extends Layout implements Provider
{
    /** @var array */
    protected $json_config;

    public function __construct(array $config, $chunk_size)
    {
        parent::__construct($config, $chunk_size);

        if($this->getFileDir() && file_exists($this->getFileDir())){
            $this->json_config = json_decode(file_get_contents($this->getFileDir()), true);
        }

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
        $sound = $push['sound'] ?? null;

        $payload = [
            'image' => $image,
            'title' => $title,
            'message' => $text,
            'sound' => $sound,
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

        try {
            $errors = [];

            foreach(array_chunk($tokens, ceil(count($tokens)/ceil($this->chunk_size / 4))) as $chunk) {
                $result = \Amp\Promise\wait(parallelMap($chunk, function ($token) use ($message, $client) {
                    $user_key = $token['user_key'];
                    $token = $token['token'];

                    try {
                        $message = clone $message;
                        $message->setToken($token);

                        $send_body = new \Google_Service_FirebaseCloudMessaging_SendMessageRequest();
                        $send_body->setMessage($message);

                        $client->projects_messages->send('projects/' . $this->json_config['project_id'], $send_body);
                    } catch (\Throwable $e) {
                        $error = json_decode($e->getMessage(), true);
                        if (is_array($error)) {
                            $error = $error['error'];
                            error_log("GOOGLE $token " . $error['message']);

                            if (in_array($error['code'], [400, 404])) {
                                return $user_key;
                            }
                        }
                    }
                }));

                if($result) {
                    $errors = array_merge($errors, $result);
                }
            }
            return $errors;
        } catch (MultiReasonException $e) {
            error_log($e->getMessage());
        }
    }
}
