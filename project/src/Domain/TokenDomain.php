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

    public function saveToken($user, $provider, $token)
    {
        $push_token = $this->getRepository()->getOneByCustomerToken($user);

        $push_token->fromArray([$provider => $token], PushTokenTableMap::TYPE_FIELDNAME);
        $push_token->save();
    }

    public function removeToken($user, $provider)
    {
        $push_token = $this->getRepository()->getOneByCustomerToken($user);

        $push_token->fromArray([$provider => null], PushTokenTableMap::TYPE_FIELDNAME);
        $push_token->save();
    }
}