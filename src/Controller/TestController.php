<?php

namespace SamuelPouzet\Api\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;

class TestController extends AbstractActionController
{

    public function getAllAction(): JsonModel
    {
        return new JsonModel([]);
    }
}