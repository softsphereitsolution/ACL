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

// API route group
$router->group(['prefix' => 'api'], function () use ($router) {
   // Matches "/api/register
   $router->post('register', 'AuthController@register');
   $router->post('login', 'AuthController@login');
   $router->get('profile', 'UserController@profile');
   $router->get('subscriber', 'UserController@allSubscriber');
   $router->post('subscribe', 'AuthController@addSubscriber');
   $router->put('update-subscriber', 'UserController@updateSubscriber');
   $router->put('confirm-subscriber', 'UserController@confirmSubscriber');
   $router->delete('delete-subscriber', 'UserController@deleteSubscriber');
   
});