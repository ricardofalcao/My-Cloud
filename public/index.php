<?php

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);

set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

$router = new Core\Router();
$router->get('/auth/login', 'Auth', 'login', ['NotAuthenticated']);
$router->post('/auth/login', 'Auth', 'authenticate', ['NotAuthenticated']);
$router->get('/auth/logout', 'Auth', 'logout');

$router->get('/auth/register', 'Auth', 'register', ['NotAuthenticated']);
$router->post('/auth/register', 'Auth', 'signup', ['NotAuthenticated']);

$router->get('/drive/files', 'Drive', 'files', ['Authenticated']);
$router->get('/drive/files/{id}', 'Drive', 'files', ['Authenticated']);

$router->post('/drive/files', 'Drive', 'filesPost', ['Authenticated']);
$router->post('/drive/files/{id}', 'Drive', 'filesPost', ['Authenticated']);
$router->delete('/drive/files/{id}', 'Drive', 'filesDelete', ['Authenticated']);

$router->get('/drive/favorites', 'Drive', 'favorites', ['Authenticated']);
$router->post('/drive/favorites/{id}', 'Drive', 'favoritesPost', ['Authenticated']);
$router->delete('/drive/favorites/{id}', 'Drive', 'favoritesDelete', ['Authenticated']);

$router->get('/drive/shared', 'Drive', 'shared', ['Authenticated']);

$router->get('/drive/trash', 'Drive', 'trash', ['Authenticated']);
$router->post('/drive/trash/{id}', 'Drive', 'trashPost', ['Authenticated']);
$router->delete('/drive/trash/{id}', 'Drive', 'trashDelete', ['Authenticated']);

$router->get('/drive/download/{id}', 'Drive', 'download', ['Authenticated']);

$router->get('/dashboard/admin/users', 'DashboardAdmin', 'listUsers', ['Authenticated']);
$router->get('/dashboard/admin/users/{id}', 'DashboardAdmin', 'showUser', ['Authenticated']);

$router->post('/dashboard/admin/users', 'DashboardAdmin', 'createUser', ['Authenticated']);

$app = new Core\App($router);
$app->run();