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

        $client = new \Google_Service_FirebaseCloudMessaging($this->getClient());

        $notification = new \Google_Service_FirebaseCloudMessaging_Notification();
        $notification->setImage($image);
        $notification->setTitle($title);
        $notification->setBody($text);

        $android_notification = new \Google_Service_FirebaseCloudMessaging_AndroidNotification();
        $android_notification->setImage($image);
        $android_notification->setTitle($title);
        $android_notification->setBody($text);

        $android = new \Google_Service_FirebaseCloudMessaging_AndroidConfig();
        $android->setData($push['payload'] ?? []);
        $android->setNotification($android_notification);

        $message = new \Google_Service_FirebaseCloudMessaging_Message();
        $message->setNotification($notification);
        $message->setData($push['payload'] ?? []);
        $message->setAndroid($android);

        foreach ($tokens as $token){
            $message->setToken($token);

            $send_body = new \Google_Service_FirebaseCloudMessaging_SendMessageRequest();
            $send_body->setMessage($message);

            $client->projects_messages->send('projects/' . $this->json_config['project_id'], $send_body);
        }
    }
}
