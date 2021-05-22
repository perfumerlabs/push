<?php

namespace Push\Domain;


use Push\Model\PushLog;

class LogDomain
{
    public function log(array $users, array $push)
    {
        $log = new PushLog();
        $log->setUsers($users);
        $log->setPush($push);
        $log->save();
    }
}