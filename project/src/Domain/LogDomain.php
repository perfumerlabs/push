<?php

namespace Push\Domain;


use Push\Model\PushLog;

class LogDomain
{
    public function log(?array $users, array $push, ?array $errors)
    {
        $log = new PushLog();
        $log->setUsers($users);
        $log->setPush($push);
        $log->setErrors($errors);
        $log->save();
    }
}