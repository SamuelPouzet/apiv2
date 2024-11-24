<?php

namespace SamuelPouzet\Api;

class Module
{

    public function getConfig(): array
    {
        return include dirname(__DIR__) . '/config/module.config.php';
    }

}