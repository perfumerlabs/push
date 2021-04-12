<?php

namespace Push\Facade;


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

    public function __construct(TokenDomain $token_domain, TokenRepository $token_repository, Google $google, Huawei $huawei, Apple $apple)
    {
        $this->repository = $token_repository;
        $this->domain = $token_domain;
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

    public function sendPush(array $tokens, array $push)
    {
        $push_tokens = $this->getRepository()->getPushTokens($tokens);

        $errors = [];

        foreach (PushToken::getProviders() as $provider){
            if($push_tokens[$provider]){
                /** @var \Push\Service\Providers\Provider $provider_service */
                $provider_service = $this->$provider;
                $delete = $provider_service->send($push_tokens[$provider], $push);

                if($delete){
                    $this->getDomain()->removeTokens($delete, $provider);
                    $errors[$provider] = $delete;
                }
            }
        }

        return $errors;
    }
}