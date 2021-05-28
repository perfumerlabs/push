<?php

namespace Push\Facade;


use Push\Domain\LogDomain;
use Push\Domain\TokenDomain;
use Push\Model\PushToken;
use Push\Repository\TokenRepository;
use Push\Service\Providers\Apple;
use Push\Service\Providers\Google;
use Push\Service\Providers\Huawei;

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

    public function __construct(TokenDomain $token_domain, LogDomain $log_domain, TokenRepository $token_repository, Google $google, Huawei $huawei, Apple $apple)
    {
        $this->repository = $token_repository;
        $this->domain = $token_domain;
        $this->log = $log_domain;
        $this->apple = $apple;
        $this->google = $google;
        $this->huawei = $huawei;
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

    public function sendPush(array $tokens, array $push)
    {
        $push_tokens = $this->getRepository()->getPushTokens($tokens);

        $errors = [];

        foreach (PushToken::getProviders() as $provider){
            if($push_tokens[$provider]){
                /** @var \Push\Service\Providers\Provider $provider_service */
                $provider_service = $this->$provider;

                $delete = [];

//                foreach(array_chunk($push_tokens[$provider], 200) as $user_keys) {
                    $delete = array_merge($delete, $provider_service->send($push_tokens[$provider], $push));
//                }

                if($delete){
                    foreach ($delete as $key => $item){
                        if(!$item){
                            unset($delete[$key]);
                        }
                    }
                    if($delete) {
                        $this->getDomain()->removeTokens($delete, $provider);
                        $errors[$provider] = $delete;
                    }
                }
            }
        }

        $this->log->log($push_tokens, $push, $errors);

        return $errors;
    }
}