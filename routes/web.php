<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(['middleware' => 'jwt.auth'], function () use ($router) {

    $router->post('category', ['uses' => 'CategoryController@create']);
    $router->patch('category/{id}', ['uses' => 'CategoryController@update']);
    $router->get('category', ['uses' => 'CategoryController@all']);
    $router->get('category/{id}', ['uses' => 'CategoryController@one']);
    $router->delete('category/{id}', ['uses' => 'CategoryController@del']);
});


$router->post('auth/login', ['uses' => 'AuthController@authenticate']);
