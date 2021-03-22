<?php


namespace Push\Controller;


class SendController extends LayoutController
{
    public function post()
    {
        $customer_tokens = $this->f('customer_tokens');

        foreach ($customer_tokens as $token){
            $this->validateNotEmpty($token, 'customer_token');
            $this->validateNotRegex($token, 'customer_token', "/[-!$%^&*()+|~=`{}\[\]:\";'<>?,.\/]/");
        }

        $push = (array)$this->f('push');

        $this->validateNotEmpty($push, 'push');

        try {
            /** @var \Push\Facade\TokenFacade $token_facade */
            $token_facade = $this->s('facade.token');
            $errors = $token_facade->sendPush($customer_tokens, $push);

            if($errors){
                $this->setContent(['errors' => $errors]);
            }
        }catch (\Throwable $e){
            $this->setErrorMessageAndExit($e->getMessage());
        }
    }
}
