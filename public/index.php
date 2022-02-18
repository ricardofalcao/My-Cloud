<?php

/**
 * Composer
 */

use Core\Asset;

require dirname(__DIR__) . '/vendor/autoload.php';


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);

set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

$router = new Core\Router(Asset::path(''));
$router->get('/auth/login', 'Auth', 'login', ['NotAuthenticated']);
$router->post('/auth/login', 'Auth', 'authenticate', ['NotAuthenticated']);
$router->get('/auth/logout', 'Auth', 'logout');

$router->get('/auth/register', 'Auth', 'register', ['NotAuthenticated']);
$router->post('/auth/register', 'Auth', 'signup', ['NotAuthenticated']);

$router->get('/drive/files', 'Drive', 'files', ['Authenticated']);
$router->get('/drive/files/{id}', 'Drive', 'files', ['Authenticated']);

$router->post('/drive/files', 'Drive', 'filesPost', ['Authenticated']);
$router->post('/drive/files/{id}', 'Drive', 'filesPost', ['Authenticated']);
$router->put('/drive/files/{id}', 'Drive', 'filesPut', ['Authenticated']);
$router->delete('/drive/files/{id}', 'Drive', 'filesDelete', ['Authenticated']);

$router->get('/drive/favorites', 'Drive', 'favorites', ['Authenticated']);

$router->post('/drive/favorites/{id}', 'Drive', 'favoritesPost', ['Authenticated']);
$router->delete('/drive/favorites/{id}', 'Drive', 'favoritesDelete', ['Authenticated']);

$router->get('/drive/shared', 'Drive', 'shared', ['Authenticated']);
$router->post('/drive/shared/{id}', 'Drive', 'sharedPost', ['Authenticated']);
$router->delete('/drive/shared/{id}', 'Drive', 'sharedDelete', ['Authenticated']);

$router->get('/drive/trash', 'Drive', 'trash', ['Authenticated']);
$router->post('/drive/trash/{id}', 'Drive', 'trashPost', ['Authenticated']);
$router->delete('/drive/trash/{id}', 'Drive', 'trashDelete', ['Authenticated']);

$router->get('/drive/download', 'DriveDownload', 'download', ['Authenticated']);

$router->get('/dashboard/admin/users', 'DashboardAdmin', 'users', ['Authenticated', 'IsAdmin']);
$router->post('/dashboard/admin/users', 'DashboardAdmin', 'usersPost', ['Authenticated', 'IsAdmin']);
$router->delete('/dashboard/admin/users/{id}', 'DashboardAdmin', 'usersDelete', ['Authenticated', 'IsAdmin']);

$router->get('/dashboard/admin/stats', 'DashboardAdmin', 'stats', ['Authenticated', 'IsAdmin']);
$router->get('/dashboard/admin/stats/api', 'DashboardAdmin', 'statsApi', ['Authenticated', 'IsAdmin']);

$router->get('/dashboard/user/profile', 'DashboardUser', 'profile', ['Authenticated']);
$router->post('/dashboard/user/profile/password', 'DashboardUser', 'profilePassword', ['Authenticated']);
$router->post('/dashboard/user/profile/name', 'DashboardUser', 'profileName', ['Authenticated']);
$router->get('/dashboard/user/stats', 'DashboardUser', 'stats', ['Authenticated']);

$app = new Core\App($router);
$app->run();