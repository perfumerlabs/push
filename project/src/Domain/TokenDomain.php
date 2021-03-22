<?php

namespace Push\Domain;


use Push\Model\Map\PushTokenTableMap;
use Push\Repository\TokenRepository;

class TokenDomain
{
    /**
     * @var TokenRepository
     */
    protected $repository;

    public function __construct(TokenRepository $token_repository)
    {
        $this->repository = $token_repository;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function saveToken($customer_token, $provider, $token)
    {
        $push_token = $this->getRepository()->getOneByCustomerToken($customer_token);

        $push_token->fromArray([$provider . '_token' => $token], PushTokenTableMap::TYPE_FIELDNAME);
        $push_token->save();
    }

    public function removeToken($customer_token, $provider)
    {
        $push_token = $this->getRepository()->getOneByCustomerToken($customer_token);

        $push_token->fromArray([$provider . '_token' => null], PushTokenTableMap::TYPE_FIELDNAME);
        $push_token->save();
    }
}