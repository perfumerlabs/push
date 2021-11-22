<?php

namespace Push\Facade;


use Project\Model\GoRush\Notifications;
use Push\Domain\LogDomain;
use Push\Domain\TokenDomain;
use Push\Model\PushToken;
use Push\Repository\TokenRepository;
use Push\Service\GoRush;
use Push\Service\Providers\Apple;
use Push\Service\Providers\Google;
use Push\Service\Providers\Huawei;
use Push\Service\Providers\Provider;
use Push\Service\Queue;

class TokenFacade
{
    /**
     * @var TokenRepository
     */
    protected TokenRepository $repository;

    /**
     * @var TokenDomain
     */
    protected TokenDomain $domain;

    /**
     * @var LogDomain
     */
    protected LogDomain $log;

    /**
     * @var Apple
     */
    protected Apple $apple;

    /**
     * @var Google
     */
    protected Google $google;

    /**
     * @var Huawei
     */
    protected Huawei $huawei;

    /**
     * @var Queue
     */
    protected Queue $queue;

    /**
     * @var int
     */
    protected int $chunk_size;

    /**
     * @var string
     */
    protected string $engine;

    /**
     * @var GoRush
     */
    protected GoRush $gorush;

    const ENGINE_GORUSH = 'gorush';
    const ENGINE_COMMON = 'common';

    public function __construct(TokenDomain $token_domain, LogDomain $log_domain, TokenRepository $token_repository, Google $google, Huawei $huawei, Apple $apple, Queue $queue, array $config, GoRush $gorush)
    {
        $this->repository = $token_repository;
        $this->domain = $token_domain;
        $this->log = $log_domain;
        $this->apple = $apple;
        $this->google = $google;
        $this->huawei = $huawei;
        $this->queue = $queue;

        $this->chunk_size = $config['chunk_size'];
        $this->engine = $config['engine'];
        $this->gorush = $gorush;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function getLog()
    {
        return $this->log;
    }

    public function getGoRush(): GoRush
    {
        return $this->gorush;
    }

    public function send(array $tokens, array $push, ?string $queue_worker)
    {
        $push_tokens = $this->getRepository()->getPushTokens($tokens);

        if ($this->engine === $this::ENGINE_GORUSH){
            $this->pushByGoRush($push_tokens, $push);
        }else{
            $this->pushByCommon($push_tokens, $push, $queue_worker);
        }
    }

    protected function pushByGoRush(array $push_tokens, array $push)
    {
        $notifications = new Notifications();
        foreach (PushToken::getProviders() as $provider){
            if ($tokens = $push_tokens[$provider]) {
                $tokens = array_column($tokens, 'token');
                $model = null;
                foreach(array_chunk($tokens, 1000) as $chunk) {
                    switch ($provider) {
                        case PushToken::PROVIDER_APPLE:
                            $model = new \Project\Model\GoRush\Apple($chunk, $push);
                            break;
                        case PushToken::PROVIDER_GOOGLE:
                            $model = new \Project\Model\GoRush\Google($chunk, $push);
                            break;
                        case PushToken::PROVIDER_WEB:
                            $model = new \Project\Model\GoRush\Web($chunk, $push);
                            break;
                        case PushToken::PROVIDER_HUAWEI:
                            $model = new \Project\Model\GoRush\Huawei($chunk, $push);
                            break;
                    }
                    if ($model) {
//                    var_dump($model->toArray());exit();
                        $notifications->addNotifications($model->toArray());
                    }
                }
            }
        }

        if ($notifications->getNotifications()){
            $this->getGoRush()->send($notifications);
        }
    }

    protected function pushByCommon(array $push_tokens, $push, $queue_worker)
    {
        foreach (PushToken::getProviders() as $provider){
            if ($push_tokens[$provider]) {
                if ($provider !== PushToken::PROVIDER_HUAWEI && $queue_worker && count($push_tokens[$provider]) > 1) {
                    foreach (array_chunk($push_tokens[$provider], $this->chunk_size) as $tokens) {
                        $this->queue->pushQueue([
                            'provider' => $provider,
                            'tokens' => $tokens,
                            'push' => $push
                        ], $queue_worker);
                    }
                } else {
                    $errors[$provider] = $this->pushCommon($push_tokens[$provider], $provider, $push);
                }
            }
        }
    }

    protected function pushCommon($tokens, $provider, $push)
    {
        /** @var Provider $provider_service */
        $provider_service = $this->$provider;

        $delete = $provider_service->send($tokens, $push);

        if($delete){
            foreach ($delete as $key => $item){
                if(!$item){
                    unset($delete[$key]);
                }
            }
        }

        return $delete;
    }
}