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
        $user = $this->f('user');

        $this->validateNotEmpty($user, 'user');
        $this->validateNotRegex($user, 'user', "/[-!$%^&*()+|~=`{}\[\]:\";'<>?,.\/]/");

        try {
            /** @var TokenRepository $repository */
            $repository = $this->s('repository.token');
            $customer = $repository->getOneByCustomerToken($user);

            $this->setContent(['tokens' => [
                PushToken::PROVIDER_APPLE => $customer->getApple(),
                PushToken::PROVIDER_GOOGLE => $customer->getGoogle(),
                PushToken::PROVIDER_HUAWEI => $customer->getHuawei(),
                PushToken::PROVIDER_WEB => $customer->getWeb(),
            ]]);
        } catch (\Throwable $e) {
            $this->forward('error', 'internalServerError', [$e]);
        }
    }

    public function post()
    {
        $user = $this->f('user');
        $provider = $this->f('provider');
        $token = $this->f('token');

        $this->validateNotEmpty($user, 'user');
        $this->validateNotEmpty($provider, 'provider');
        $this->validateNotEmpty($token, 'token');
        $this->validateOnConst($provider, 'provider', PushToken::getProviders());
        $this->validateNotRegex($user, 'user', "/[-!$%^&*()+|~=`{}\[\]:\";'<>?,.\/]/");

        try {
            /** @var TokenDomain $domain */
            $domain = $this->s('domain.token');
            $domain->saveToken($user, $provider, $token);

            $this->setContent(['token' => [
                'user' => $user,
                'provider' => $provider,
                'token' => $token,
            ]]);
        } catch (\Throwable $e) {
            $this->forward('error', 'internalServerError', [$e]);
        }
    }

    public function delete()
    {
        $user = $this->f('user');
        $provider = $this->f('provider');

        $this->validateNotEmpty($user, 'user');
        $this->validateNotEmpty($provider, 'provider');
        $this->validateOnConst($provider, 'provider', PushToken::getProviders());
        $this->validateNotRegex($user, 'user', "/[-!$%^&*()+|~=`{}\[\]:\";'<>?,.\/]/");

        try {
            /** @var TokenDomain $domain */
            $domain = $this->s('domain.token');
            $domain->removeToken($user, $provider);
        } catch (\Throwable $e) {
            $this->forward('error', 'internalServerError', [$e]);
        }
    }
}
