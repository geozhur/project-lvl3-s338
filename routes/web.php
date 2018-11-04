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


$router->get('/', [
    'as' => 'index',  function () use ($router) {
        return view('index');
    }
]);

$router->get('/about', [
    'as' => 'about',  function () use ($router) {
        return view('about');
    }
]);

$router->get('/domains/{id:[0-9]+}', [
    'as' => 'domains.show', 'uses' => 'DomainsController@show'
]);

$router->post('/domains', [
    'as' => 'domains.store', 'uses' => 'DomainsController@store'
]);

$router->get('/domains', [
    'as' => 'domains.store', 'uses' => 'DomainsController@index'
]);

$router->get('/domains/json/{id:[0-9]+}', [
    'as' => 'domains.json', 'uses' => 'DomainsController@json'
]);
