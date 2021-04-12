<?php


namespace Push\Controller;


class SendController extends LayoutController
{
    public function post()
    {
        $users = $this->f('user');

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

        try {
            /** @var \Push\Facade\TokenFacade $token_facade */
            $token_facade = $this->s('facade.token');
            $errors = $token_facade->sendPush($users, $push);

            if($errors){
                $this->setContent(['errors' => $errors]);
            }
        }catch (\Throwable $e){
//            var_dump($e->getMessage());
        }
    }
}
