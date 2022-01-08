<?php

namespace Core;

class App
{
    protected $router;

    public function __construct($router)
    {
        $this->router = $router;
    }

    public function run() {
        $this->router->handleRequest();
    }
}
