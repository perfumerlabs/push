<?php

namespace Push\Repository;

use Propel\Runtime\ActiveQuery\Criteria;
use Push\Model\PushTokenQuery;

class TokenRepository
{
    public function getOneByCustomerToken($user)
    {
        return PushTokenQuery::create()
            ->filterByUserKey($user)
            ->findOneOrCreate();
    }

    public function getPushTokens(array $users)
    {
        $push_tokens = PushTokenQuery::create()
            ->select(['user_key', 'google', 'huawei', 'apple', 'web'])
            ->filterByUserKey($users, Criteria::IN)
            ->find()
            ->getData();

        $result = [
            'google' => [],
            'huawei' => [],
            'apple' => [],
            'web' => [],
        ];

        foreach ($push_tokens as $tokens){
            $user_key = $tokens['user_key'];

            if($tokens['google']){
                $result['google'][] = [
                    'user_key' => $user_key,
                    'token' => $tokens['google']
                ];
            }
            if($tokens['huawei']){
                $result['huawei'][] = [
                    'user_key' => $user_key,
                    'token' => $tokens['huawei']
                ];
            }
            if($tokens['apple']){
                $result['apple'][] = [
                    'user_key' => $user_key,
                    'token' => $tokens['apple']
                ];
            }
            if($tokens['web']){
                $result['web'][] = [
                    'user_key' => $user_key,
                    'token' => $tokens['google']
                ];
            }
        }

        return $result;
    }
}