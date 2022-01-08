<?php

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);

/*set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');*/

$router = new Core\Router();
$router->get('/auth/login', 'Auth', 'login');

$router->get('/drive/files', 'Drive', 'files');
$router->get('/drive/favorites', 'Drive', 'favorites');
$router->get('/drive/shared', 'Drive', 'shared');
$router->get('/drive/trash', 'Drive', 'trash');

$app = new Core\App($router);
$app->run();