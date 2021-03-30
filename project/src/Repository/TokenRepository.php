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
            ->filterByUserKey($users, Criteria::IN)
            ->select(['google', 'huawei', 'apple', 'web'])
            ->find()
            ->getData();

        $result = [
            'google' => [],
            'huawei' => [],
            'apple' => [],
            'web' => [],
        ];

        foreach ($push_tokens as $tokens){
            if($tokens['google']){
                $result['google'][] = $tokens['google'];
            }
            if($tokens['huawei']){
                $result['huawei'][] = $tokens['huawei'];
            }
            if($tokens['apple']){
                $result['apple'][] = $tokens['apple'];
            }
            if($tokens['web']){
                $result['web'][] = $tokens['web'];
            }
        }

        return $result;
    }
}