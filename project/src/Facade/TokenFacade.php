<?php

namespace Push\Facade;


use Push\Domain\LogDomain;
use Push\Domain\TokenDomain;
use Push\Model\PushToken;
use Push\Repository\TokenRepository;
use Push\Service\Providers\Apple;
use Push\Service\Providers\Google;
use Push\Service\Providers\Huawei;
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

    public function __construct(TokenDomain $token_domain, LogDomain $log_domain, TokenRepository $token_repository, Google $google, Huawei $huawei, Apple $apple, Queue $queue, $chunk_size)
    {
        $this->repository = $token_repository;
        $this->domain = $token_domain;
        $this->log = $log_domain;
        $this->apple = $apple;
        $this->google = $google;
        $this->huawei = $huawei;
        $this->queue = $queue;
        $this->chunk_size = $chunk_size;
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

    public function sendPush(array $tokens, array $push, ?string $queue_worker)
    {
        $push_tokens = $this->getRepository()->getPushTokens($tokens);

//        $errors = [];

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
                    $errors[$provider] = $this->push($push_tokens[$provider], $provider, $push);
                }
            }
        }

//        $this->log->log($push_tokens, $push, $errors);

//        return $errors;
    }

    public function push($tokens, $provider, $push)
    {
        /** @var \Push\Service\Providers\Provider $provider_service */
        $provider_service = $this->$provider;

        $delete = $provider_service->send($tokens, $push);

        if($delete){
            foreach ($delete as $key => $item){
                if(!$item){
                    unset($delete[$key]);
                }
            }

            if($delete) {
//                $this->getDomain()->removeTokens($delete, $provider);
            }
        }

//        $this->log->log($tokens, $push, null);

        return $delete;
    }
}