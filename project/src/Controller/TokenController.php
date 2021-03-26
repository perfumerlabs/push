<?php


namespace Push\Controller;

use Propel\Runtime\Propel;
use Push\Domain\TokenDomain;
use Push\Model\Map\PushTokenTableMap;
use Push\Model\PushToken;
use Push\Repository\TokenRepository;

class TokenController extends LayoutController
{
    public function get()
    {
        $customer_token = $this->f('customer_token');
        $provider = $this->f('provider');

        $this->validateNotEmpty($customer_token, 'customer_token');
        $this->validateNotEmpty($provider, 'provider');
        $this->validateOnConst($provider, 'provider', PushToken::getProviders());
        $this->validateNotRegex($customer_token, 'customer_token', "/[-!$%^&*()+|~=`{}\[\]:\";'<>?,.\/]/");

        try {
            /** @var TokenRepository $repository */
            $repository = $this->s('repository.token');
            $customer = $repository->getOneByCustomerToken($customer_token);

            $this->setContent(['tokens' => [
                PushToken::PROVIDER_APPLE => $customer->getAppleToken(),
                PushToken::PROVIDER_GOOGLE => $customer->getGoogleToken(),
                PushToken::PROVIDER_HUAWEI => $customer->getHuaweiToken(),
            ]]);
        } catch (\Throwable $e) {
            $this->forward('error', 'internalServerError', [$e]);
        }
    }

    public function post()
    {
        $customer_token = $this->f('customer_token');
        $provider = $this->f('provider');
        $token = $this->f('token');

        $this->validateNotEmpty($customer_token, 'customer_token');
        $this->validateNotEmpty($provider, 'provider');
        $this->validateNotEmpty($token, 'token');
        $this->validateOnConst($provider, 'provider', PushToken::getProviders());
        $this->validateNotRegex($customer_token, 'customer_token', "/[-!$%^&*()+|~=`{}\[\]:\";'<>?,.\/]/");

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

    public function delete()
    {
        $customer_token = $this->f('customer_token');
        $provider = $this->f('provider');

        $this->validateNotEmpty($customer_token, 'customer_token');
        $this->validateNotEmpty($provider, 'provider');
        $this->validateOnConst($provider, 'provider', PushToken::getProviders());
        $this->validateNotRegex($customer_token, 'customer_token', "/[-!$%^&*()+|~=`{}\[\]:\";'<>?,.\/]/");

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
