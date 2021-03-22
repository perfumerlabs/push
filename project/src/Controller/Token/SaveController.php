<?php


namespace Push\Controller\Token;

use Propel\Runtime\Propel;
use Push\Controller\LayoutController;
use Push\Domain\TokenDomain;
use Push\Model\Map\PushTokenTableMap;
use Push\Model\PushToken;

class SaveController extends LayoutController
{
    public function post()
    {
        $customer_token = $this->f('customer_token');
        $provider = $this->f('provider');
        $token = $this->f('token');

        $this->validateNotEmpty($customer_token, 'customer_token');
        $this->validateNotEmpty($provider, 'provider');
        $this->validateNotEmpty($token, 'token');
        $this->validateOnConst($provider, 'provider', PushToken::getProviders());

        $con = Propel::getWriteConnection(PushTokenTableMap::DATABASE_NAME);
        $con->beginTransaction();

        try {
            /** @var TokenDomain $domain */
            $domain = $this->s('domain.token');
            $domain->saveToken($customer_token, $provider, $token);

            $this->setContent(['token' => $token]);

            $con->commit();
        } catch (\Throwable $e) {
            $con->rollBack();

            $this->forward('error', 'internalServerError', [$e]);
        }
    }
}
