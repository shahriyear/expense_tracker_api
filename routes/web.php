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

    $router->post('category', 'CategoryController@create');
    $router->patch('category/{id}', 'CategoryController@update');
    $router->get('category', 'CategoryController@all');
    $router->get('category/{id}', 'CategoryController@one');
    $router->delete('category/{id}', 'CategoryController@del');
});


$router->post('auth/login', 'AuthController@authenticate');
