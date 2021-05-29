<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/sortData', 'sortOutpostData@getFilterData');

$router->group(['prefix' => 'v1'], function () use ($router) {

    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('login', 'AuthAPIController@postLogin');
        $router->group(['middleware' => 'auth'], function () use ($router) {
            $router->post('logout', 'AuthAPIController@postLogout');
        });
    });

    $router->group(['prefix' => 'customer', 'middleware' => ['auth']], function () use ($router) {
        $router->post('/',  'APIController@storeNewCustomer');
        $router->get('{id}', 'APIController@getCustomer');
        $router->patch('{id}', 'APIController@updateCustomer');

        $router->delete('{id}', 'APIController@deleteCustomer');
    });

    $router->group(['prefix' => 'todo', 'middleware' => ['auth']], function () use ($router) {
        $router->post('/', 'FirebaseAPIController@postAddTODOList');
        $router->get('/list', 'FirebaseAPIController@getTODOList');

        $router->get('{id}', 'FirebaseAPIController@getTODO');
        $router->post('{id}', 'FirebaseAPIController@postUpdateTODOList');
        $router->delete('{id}', 'FirebaseAPIController@deleteTODO');
    });

    $router->get('customers','APIController@getAllCustomers');
});

$router->get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});
