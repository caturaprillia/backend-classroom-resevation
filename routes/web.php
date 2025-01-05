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

$router->post('register', 'AuthController@register');
$router->post('login', 'AuthController@login');

$router->group(['prefix' => 'classrooms'], function() use ($router) {
    $router->post('/', 'ClassroomController@store');  // Menambahkan classroom baru
    $router->get('/', 'ClassroomController@index');  // Mengambil semua classrooms
    $router->get('{id}', 'ClassroomController@show');  // Mengambil classroom berdasarkan ID
    $router->put('{id}', 'ClassroomController@update');  // Mengupdate classroom berdasarkan ID
    $router->delete('{id}', 'ClassroomController@destroy');  // Menghapus classroom berdasarkan ID
});

$router->group(['prefix' => 'reservations'], function() use ($router) {
    $router->post('/', 'ReservationController@store');  // Menambahkan reservasi baru
    $router->get('/', 'ReservationController@index');  // Mengambil semua reservasi
    $router->get('{id}', 'ReservationController@show');  // Mengambil reservasi berdasarkan ID
    $router->put('{id}', 'ReservationController@update');  // Mengupdate reservasi berdasarkan ID
    $router->delete('{id}', 'ReservationController@destroy');  // Menghapus reservasi berdasarkan ID
});

