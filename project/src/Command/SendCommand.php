<?php

namespace Push\Command;

use function Amp\ParallelFunctions\parallelMap;
use Perfumer\Framework\Controller\PlainController;
use Perfumer\Framework\Router\ConsoleRouterControllerHelpers;

class SendCommand extends PlainController
{
    use ConsoleRouterControllerHelpers;

    public function action()
    {
        $user_key = $this->o('token', 't');
        $count = $this->o('count', 'c', 1);
        $provider = $this->o('provider', 'p', 'google');
        $title = $this->o('title', 'tl', 'test');
        $text = $this->o('text', 'tx', 'test');
        $track = $this->o('track', 'tr', 'test');
        $event = $this->o('event', 'e', 'order');
        $sound = $this->o('sound', 's', null);

        $push = [
            'title' => $title,
            'text' => $text,
            'image' => null,
            'payload' => [
                'track' => $track,
                'event' => $event,
            ],
            'sound' => $sound,
        ];

        if($event === 'order'){
            $push['payload'] = array_merge($push['payload'], [
                'data' => [
                    'id' => 3,
                    'order_id' => 2
                ]
            ]);
        }

        try {
            if (!$user_key) {
                throw new \Exception('Empty token param', 500);
            }
        }catch (\Exception $e){
            echo $e;exit();
        }

        $token = \Push\Model\PushTokenQuery::create()
            ->select('user_key')
            ->filterByUserKey($user_key);

        switch ($provider){
            case \Push\Model\PushToken::PROVIDER_GOOGLE:
                $token = $token
                    ->filterByGoogle(null, \Propel\Runtime\ActiveQuery\Criteria::ISNOTNULL)
                    ->addAsColumn('token', \Push\Model\PushToken::PROVIDER_GOOGLE);
                break;
            case \Push\Model\PushToken::PROVIDER_APPLE:
                $token = $token
                    ->filterByApple(null, \Propel\Runtime\ActiveQuery\Criteria::ISNOTNULL)
                    ->addAsColumn('token', \Push\Model\PushToken::PROVIDER_APPLE);
                break;
            case \Push\Model\PushToken::PROVIDER_HUAWEI:
                $token = $token
                    ->filterByHuawei(null, \Propel\Runtime\ActiveQuery\Criteria::ISNOTNULL)
                    ->addAsColumn('token', \Push\Model\PushToken::PROVIDER_HUAWEI);
                break;
            case \Push\Model\PushToken::PROVIDER_WEB:
                $token = $token
                    ->filterByWeb(null, \Propel\Runtime\ActiveQuery\Criteria::ISNOTNULL)
                    ->addAsColumn('token', \Push\Model\PushToken::PROVIDER_WEB);
                break;
        }

        $token = $token->findOne();
        if(!$token){
            exit();
        }
        $tokens = [];

        if($count > 1){
            for ($i = 0; $i < $count; $i++){
                $tokens[] = $token;
            }
        }else{
            $tokens[] = $token;
        }

        if($tokens){
//            /** @var \Push\Service\Providers\Provider $provider_service */
//            $provider_service = $this->s('providers.' . $provider);
//
//            $errors = $provider_service->send($tokens, $push);
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

            putenv("GOOGLE_APPLICATION_CREDENTIALS=/opt/config/google_prod.json");
            $client = new \Google\Client();
            $client->useApplicationDefaultCredentials();
            $client->addScope(\Google_Service_FirebaseCloudMessaging::CLOUD_PLATFORM);


            $client = new \Google_Service_FirebaseCloudMessaging($client);

            try {
                $errors = [];

                foreach(array_chunk($tokens, 200) as $chunk) {
                    $result = \Amp\Promise\wait(\Amp\ParallelFunctions\parallelMap($tokens, function ($token) use ($payload, $client) {
                        $user_key = $token['user_key'];
                        $token = $token['token'];
                        $id = (string)rand(0, 9999);
                        $data = [
                            'id' => $id,
                            'order_id' => $id
                        ];
                        $payload['data'] = json_encode($data);
                        try {
                            $android = new \Google_Service_FirebaseCloudMessaging_AndroidConfig();
                            $android->setData($payload);

                            $message = new \Google_Service_FirebaseCloudMessaging_Message();
                            $message->setData($payload);
                            $message->setAndroid($android);
                            $message->setToken($token);

                            $send_body = new \Google_Service_FirebaseCloudMessaging_SendMessageRequest();
                            $send_body->setMessage($message);

                            $client->projects_messages->send('projects/naimikz-708f1', $send_body);
                        } catch (\Throwable $e) {
                            return $e->getMessage();
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
                    var_dump($result);
//                    $errors = array_merge($errors, $result);
                }
                return $errors;
            } catch (\Amp\MultiReasonException $e) {
                var_dump($e->getReasons());exit();
                error_log($e->getMessage());
            }
        }
    }
}
