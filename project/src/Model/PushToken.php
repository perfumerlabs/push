<?php

namespace Push\Model;

use Push\Model\Base\PushToken as BasePushToken;

/**
 * Skeleton subclass for representing a row from the 'push_token' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class PushToken extends BasePushToken
{
    const PROVIDER_APPLE = 'apple';
    const PROVIDER_GOOGLE = 'google';
    const PROVIDER_HUAWEI = 'huawei';
    const PROVIDER_WEB = 'web';

    static public function getProviders()
    {
        return [self::PROVIDER_APPLE, self::PROVIDER_GOOGLE, self::PROVIDER_HUAWEI, self::PROVIDER_WEB];
    }
}
