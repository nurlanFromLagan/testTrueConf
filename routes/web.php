<?php

use Bramus\Router\Router;
use App\Controllers\UserController;

require __DIR__ . '../vendor/autoload.php';

$router = new Router();
header('Content-Type: application/json');


// получение пользователей
$router->get('api/v1/users', 'UserController@index');

// получение пользователя
$router->get('api/v1/users/{id}', 'UserController@getOne');

// создание пользователя
$router->post('api/v1/users', 'UserController@createUser');

// изменение пользователя
$router->patch('api/v1/users/{id}', 'UserController@updateUser');

// удаление пользователя
$router->delete('api/v1/users/{id}', 'UserController@deleteUser');

$router->run();