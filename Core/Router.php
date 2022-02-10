<?php

namespace Core;

class Route
{
    public $expression;

    public $httpMethod;

    public $controller;

    public $method;

    public function __construct($expression, $httpMethod, $controller, $method)
    {
        $this->expression = $expression;
        $this->httpMethod = $httpMethod;
        $this->controller = $controller;
        $this->method = $method;
    }
}

class Router
{

    public $basepath;

    /** @var Route[] */
    private $routes;

    public function __construct($basepath = '/')
    {
        $this->basepath = $basepath;
    }

    /*

    */

    private function prepareExpression($path) {
        // Add basepath to matching string
        if ($this->basepath != '' && $this->basepath != '/') {
            $path = '(' . $this->basepath . ')' . $path;
        }

        // Convert the route to a regular expression: escape forward slashes
        $path = preg_replace('/\//', '\\/', $path);

        // Convert variables e.g. {controller}
        $path = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?\'\1\'[a-zA-Z0-9_]+)', $path);

        // Convert variables with custom regular expressions e.g. {id:\d+}
        $path = preg_replace('/\{([a-zA-Z0-9_]+):([^\}]+)\}/', '(?\'$1\'$2)', $path);

        // Add start and end delimiters, and case insensitive flag
        $path = '/^' . $path . '$/i';
        
        return $path;
    }

    public function get($path, $controller, $method)
    {
        $this->routes[] = new Route($this->prepareExpression($path), 'GET', $controller, $method);
    }

    public function post($path, $controller, $method)
    {
        $this->routes[] = new Route($this->prepareExpression($path), 'POST', $controller, $method);
    }

    public function patch($path, $controller, $method)
    {
        $this->routes[] = new Route($this->prepareExpression($path), 'PATCH', $controller, $method);
    }

    public function delete($path, $controller, $method)
    {
        $this->routes[] = new Route($this->prepareExpression($path), 'DELETE', $controller, $method);
    }

    /*

    */

    public function handleRequest() {
        // Parse current url
        $parsed_url = parse_url($_SERVER['REQUEST_URI']); //Parse Uri

        if (isset($parsed_url['path'])) {
            $url = $parsed_url['path'];
        } else {
            $url = '/';
        }

        $httpMethod = strtolower($_SERVER['REQUEST_METHOD']);

        foreach ($this->routes as $route) {
            if (preg_match($route->expression, $url, $matches)) {
                if (strtolower($route->httpMethod) == $httpMethod) {
                    $params = [];

                    foreach ($matches as $key => $match) {
                        if (is_string($key)) {
                            $params[$key] = $match;
                        }
                    }

                    $controller = 'App\Controllers\\' . $route->controller;
                    $method = $route->method;
                    
                    if (!class_exists($controller)) {
                        throw new \Exception("Could not find $controller class");
                    }

                    $instance = new $controller($params);
                    if (!method_exists($instance, $method)) {
                        throw new \Exception("Method $method not found in controller " . $controller);
                    }
            
                    call_user_func_array([$instance, $method], []);
                    
                    return;
                }
            }
        }

        throw new \Exception('Could not find route.', 404);
    }
}
