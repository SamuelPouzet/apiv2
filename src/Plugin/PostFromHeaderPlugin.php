<?php

namespace SamuelPouzet\Api\Plugin;

use Laminas\Mvc\Controller\Plugin\AbstractPlugin;

class PostFromHeaderPlugin extends AbstractPlugin
{
    protected ?array $postData = null;

    public function __invoke()
    {
        if ($this->postData) {
            return $this->postData;
        }
        $this->postData = json_decode(file_get_contents("php://input"), true);
        return $this->postData;
    }

}