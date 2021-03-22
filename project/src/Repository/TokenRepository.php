<?php

namespace Push\Repository;

use Propel\Runtime\ActiveQuery\Criteria;
use Push\Model\PushTokenQuery;

class TokenRepository
{
    public function getOneByCustomerToken($customer_token)
    {
        return PushTokenQuery::create()
            ->filterByCustomerToken($customer_token)
            ->findOneOrCreate();
    }

    public function getPushTokens(array $customer_tokens)
    {
        $push_tokens = PushTokenQuery::create()
            ->filterByCustomerToken($customer_tokens, Criteria::IN)
            ->select(['google_token', 'huawei_token', 'apple_token', 'web_token'])
            ->find()
            ->getData();

        $result = [
            'google_token' => [],
            'huawei_token' => [],
            'apple_token' => [],
            'web_token' => [],
        ];

        foreach ($push_tokens as $tokens){
            if($tokens['google_token']){
                $result['google_token'][] = $tokens['google_token'];
            }
            if($tokens['huawei_token']){
                $result['huawei_token'][] = $tokens['huawei_token'];
            }
            if($tokens['apple_token']){
                $result['apple_token'][] = $tokens['apple_token'];
            }
            if($tokens['web_token']){
                $result['web_token'][] = $tokens['web_token'];
            }
        }

        return $result;
    }
}