<?php

namespace Core;

class Route
{
    public $expression;

    public $httpMethod;

    public $controller;

    public $method;

    public $middleware;

    public function __construct($expression, $httpMethod, $controller, $method, $middleware)
    {
        $this->expression = $expression;
        $this->httpMethod = $httpMethod;
        $this->controller = $controller;
        $this->method = $method;
        $this->middleware = $middleware;
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

    private function prepareExpression($path)
    {
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

    public function get($path, $controller, $method, $middleware = [])
    {
        $this->routes[] = new Route($this->prepareExpression($path), 'GET', $controller, $method, $middleware);
    }

    public function post($path, $controller, $method, $middleware = [])
    {
        $this->routes[] = new Route($this->prepareExpression($path), 'POST', $controller, $method, $middleware);
    }

    public function patch($path, $controller, $method, $middleware = [])
    {
        $this->routes[] = new Route($this->prepareExpression($path), 'PATCH', $controller, $method, $middleware);
    }

    public function put($path, $controller, $method, $middleware = [])
    {
        $this->routes[] = new Route($this->prepareExpression($path), 'PUT', $controller, $method, $middleware);
    }

    public function delete($path, $controller, $method, $middleware = [])
    {
        $this->routes[] = new Route($this->prepareExpression($path), 'DELETE', $controller, $method, $middleware);
    }

    /*

    */

    public function handleRequest()
    {
        // Parse current url
        $parsed_url = parse_url($_SERVER['REQUEST_URI']); //Parse Uri

        if (isset($parsed_url['path'])) {
            $url = $parsed_url['path'];
        } else {
            $url = '/';
        }

        $httpMethod = strtolower($_SERVER['REQUEST_METHOD']);

        $processRoute = function ($route, $matches) {
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

            foreach ($route->middleware as $mw) {
                $middlewareClass = 'App\Middleware\\' . $mw;

                if (!class_exists($middlewareClass)) {
                    throw new \Exception("Could not find $middlewareClass class");
                }

                $middlewareInstance = new $middlewareClass();
                if (!($middlewareInstance instanceof Middleware)) {
                    throw new \Exception("$middlewareClass does not extend Middleware class");
                }

                if (!$middlewareInstance->handle()) {

                    return;
                }
            }

            call_user_func_array([$instance, $method], []);
        };

        foreach ($this->routes as $route) {
            if (preg_match($route->expression, $url, $matches)) {
                if (strtolower($route->httpMethod) == $httpMethod) {
                    $processRoute($route, $matches);
                    return;
                }
            }
        }

        header('Location: ' . Asset::path('/drive/files'), true, 303);
    }
}
