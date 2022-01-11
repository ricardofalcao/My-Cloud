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
$router->post('/auth/login', 'Auth', 'authenticate');
$router->post('/auth/logout', 'Auth', 'logout');

$router->get('/auth/test', 'Auth', 'test');

$router->get('/drive/files', 'Drive', 'files');
$router->get('/drive/favorites', 'Drive', 'favorites');
$router->get('/drive/shared', 'Drive', 'shared');
$router->get('/drive/trash', 'Drive', 'trash');

$router->get('/dashboard/admin/users', 'DashboardAdmin', 'listUsers');
$router->get('/dashboard/admin/users/{id}', 'DashboardAdmin', 'showUser');

$router->post('/dashboard/admin/users', 'DashboardAdmin', 'createUser');

$app = new Core\App($router);
$app->run();