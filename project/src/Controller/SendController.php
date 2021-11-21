<?php


namespace Push\Controller;


use Push\Facade\TokenFacade;

class SendController extends LayoutController
{
    public function post()
    {
        $users = $this->f('user');
        $queue_worker = $this->f('queue_worker');

        $this->validateNotEmpty($users, 'user');

        $users = is_array($users) ? $users : [$users];

        foreach ($users as $token){
            $this->validateNotEmpty($token, 'user');
            $this->validateNotRegex($token, 'user', "/[-!$%^&*()+|~=`{}\[\]:\";'<>?,.\/]/");
        }

        $title = (string)$this->f('title');
        $text = (string)$this->f('text');
        $image = $this->f('image');
        $payload = (array)$this->f('payload');
        $sound = $this->f('sound');
        $this->validateNotEmpty($title, 'title');
        $this->validateNotEmpty($text, 'text');

        $push = [
            'title' => $title,
            'text' => $text,
            'image' => $image,
            'payload' => $payload,
            'sound' => $sound,
        ];

        /** @var TokenFacade $token_facade */
        $token_facade = $this->s('facade.token');
        $token_facade->send($users, $push, $queue_worker);
    }
}
