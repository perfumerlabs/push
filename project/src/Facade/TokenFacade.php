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
    protected $repository;

    /**
     * @var TokenDomain
     */
    protected $domain;

    /**
     * @var Apple
     */
    protected $apple;

    /**
     * @var Google
     */
    protected $google;

    /**
     * @var Huawei
     */
    protected $huawei;

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
            if($push_tokens[$provider . '_token']){
                /** @var \Push\Service\Providers\Provider $provider_service */
                $provider_service = $this->$provider;
                $error = $provider_service->send($push_tokens[$provider . '_token'], $push);
                if($error){
                    $errors[$provider] = $error;
                }
            }
        }

        return $errors;
    }
}