<?php


namespace Push\Controller;


use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Push\Model\PushTokenQuery;

class GorushController extends LayoutController
{
    /**
     * @throws PropelException
     */
    public function post()
    {
        $type = $this->f('type');
        $platform = $this->f('platform');
        $token = $this->f('token');
        $error = $this->f('error');

        if ($type !== 'failed-push'){
            return;
        }

        if (!in_array($error, ["invalid registration token", "BadDeviceToken"])){
            return;
        }

        $token = str_replace("*", "", $token);
        $push_token = null;

        switch ($platform){
            case "android":
                $push_token = PushTokenQuery::create()
                    ->filterByGoogle("%$token%", Criteria::LIKE)
                    ->findOne();

                if(!$push_token){
                    $push_token = PushTokenQuery::create()
                        ->filterByWeb("%$token%", Criteria::LIKE)
                        ->findOne();
                }
                break;
            case "ios":
                $push_token = PushTokenQuery::create()
                    ->filterByApple("%$token%", Criteria::LIKE)
                    ->findOne();
                break;
        }

        if ($push_token){
            $push_token->delete();
        }
    }
}
