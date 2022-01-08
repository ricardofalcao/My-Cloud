<?php

namespace Core;

class App
{
    public $router;

    public function __construct($router)
    {
        $this->router = $router;
    }

    public function run() {
        $this->router->handleRequest();
    }
}
