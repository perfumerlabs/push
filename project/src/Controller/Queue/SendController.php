<?php


namespace Push\Controller\Queue;


use Push\Controller\LayoutController;

class SendController extends LayoutController
{
    public function post()
    {
        $tokens = $this->f('tokens');
        $provider = $this->f('provider');
        $push = $this->f('push');

        $this->validateNotEmpty($tokens, 'tokens');
        $this->validateNotEmpty($provider, 'provider');
        $this->validateNotEmpty($push, 'push');

        /** @var \Push\Facade\TokenFacade $token_facade */
        $token_facade = $this->s('facade.token');
        $errors = $token_facade->push($tokens, $provider, $push);

        if($errors){
            $this->setContent(['errors' => $errors]);
        }
    }
}
