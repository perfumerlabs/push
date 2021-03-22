<?php

namespace Push\Controller;

class ErrorController extends LayoutController
{
    public function badRequest($message)
    {
        $this->getExternalResponse()->setStatusCode(400);

        $this->setErrorMessage($message);
    }

    public function pageNotFound($message)
    {
        $this->getExternalResponse()->setStatusCode(404);

        $this->setErrorMessage($message);
    }

    public function accessDenied()
    {
        $this->getExternalResponse()->setStatusCode(403);

        $this->setErrorMessage('Access denied');
    }

    public function internalServerError(\Throwable $e)
    {
        $this->getExternalResponse()->setStatusCode(500);

        $this->setErrorMessage($e->getMessage());

        error_log($e->getMessage());
    }
}
