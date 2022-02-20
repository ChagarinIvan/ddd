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

$router->group(['prefix' => 'verifications'], function () use ($router) {
    $router->post('',             ['uses' => 'VerificationController@store']);
    $router->put( '{id}/confirm', ['uses' => 'VerificationController@confirm']);
});

$router->group(['middleware' => 'private'], function () use ($router) {
    $router->post('templates/render', ['as' => 'render', 'uses' => 'TemplateController@render']);
});

