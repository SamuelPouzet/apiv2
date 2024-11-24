<?php

namespace SamuelPouzet\Api\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;

// todo override with abstract jsons controller
class AuthController extends AbstractActionController
{

    public function postAction(): JsonModel
    {
        $posted = $this->plugin('postdata.plugin')();

        return new JsonModel($posted);
    }

}