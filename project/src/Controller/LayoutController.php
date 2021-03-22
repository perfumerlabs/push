<?php

namespace Push\Controller;

use Perfumer\Framework\Controller\ViewController;
use Perfumer\Framework\Router\Http\FastRouteRouterControllerHelpers;
use Perfumer\Framework\View\StatusViewControllerHelpers;
use Perfumer\Helper\Arr;

class LayoutController extends ViewController
{
    use FastRouteRouterControllerHelpers;
    use StatusViewControllerHelpers;

    protected function validateNotEmpty($var, $name)
    {
        if (!$var) {
            $this->forward('error', 'badRequest', ["\"$name\" parameter must be set"]);
        }
    }

    protected function validateOnConst($var, $name, array $const)
    {
        if (!$var) {
            $this->forward('error', 'badRequest', ["\"$name\" parameter must be set"]);
        }

        if(!in_array($var, $const)){
            $this->forward('error', 'badRequest', ["\"$name\" parameter must be set"]);
        }
    }

    protected function validateNotEmptyOneOfArray(array $vars)
    {
        foreach ($vars as $var){
            if($var){
                return;
            }
        }

        $this->forward('error', 'badRequest', [implode(', ', array_keys($vars)) . " one of this parameters must be set"]);
    }

    protected function validateRegex($var, $name, $regex)
    {
        if ($var && !preg_match($regex, $var)) {
            $this->forward('error', 'badRequest', ["\"$name\" parameter is invalid, only letters, digits and underscore signs are allowed"]);
        }
    }

    protected function validateNotRegex($var, $name, $regex)
    {
        if ($var && preg_match($regex, $var)) {
            $this->forward('error', 'badRequest', ["\"$name\" parameter is invalid, only letters, digits and underscore signs are allowed"]);
        }
    }
}
