<?php

namespace SamuelPouzet\Api\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;

class ErrorController extends AbstractActionController
{
    public function errorAction(): JsonModel
    {
        $errorStatusCode = $this->params()->fromRoute('statusCode');
        $errorMessage = $this->params()->fromRoute('message');

        $this->getResponse()->setStatusCode($errorStatusCode);

        return new JsonModel([
            'code'=> $errorStatusCode,
            'message'=> $errorMessage,
        ]);
    }

}