<?php


namespace Push\Controller\Token;

use Propel\Runtime\Propel;
use Push\Controller\LayoutController;
use Push\Domain\TokenDomain;
use Push\Model\Map\PushTokenTableMap;
use Push\Model\PushToken;

class RemoveController extends LayoutController
{
    public function post()
    {
        $customer_token = $this->f('customer_token');
        $provider = $this->f('provider');

        $this->validateNotEmpty($customer_token, 'customer_token');
        $this->validateNotEmpty($provider, 'provider');
        $this->validateOnConst($provider, 'provider', PushToken::getProviders());
        $this->validateRegex($customer_token, 'customer_token', "/[-!$%^&*()+|~=`{}\[\]:\";'<>?,.\/]/");

        $con = Propel::getWriteConnection(PushTokenTableMap::DATABASE_NAME);
        $con->beginTransaction();

        try {
            /** @var TokenDomain $domain */
            $domain = $this->s('domain.token');
            $domain->removeToken($customer_token, $provider);

            $con->commit();
        } catch (\Throwable $e) {
            $con->rollBack();
            $this->forward('error', 'internalServerError', [$e]);
        }
    }
}
