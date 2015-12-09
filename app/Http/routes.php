<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
 * Routes only unauthenticated users can access
 */
Route::group(['middleware' => 'guest'], function () {
    Route::get('/', [
        'as' => 'index', 'uses' => 'IndexController@index']);

    Route::get('login', [
        'as' => 'login', 'uses' => 'Auth\AuthController@getLogin']);

    Route::post('login', [
        'as' => 'login', 'uses' => 'Auth\AuthController@postLogin']);

    Route::get('register', [
        'as' => 'register', 'uses' => 'Auth\AuthController@getRegister']);

    Route::post('register', [
        'as' => 'register', 'uses' => 'Auth\AuthController@postRegister']);
});

/*
 * Routes only authenticated users can access
 */
Route::group(['middleware' => 'auth', 'prefix' => 'u'], function () {
    Route::get('/', [
        'as' => 'account', 'uses' => 'AccountController@index']);

    Route::get('logout', [
        'as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);

    Route::get('resources', [
        'as' => 'account.resources', 'uses' => 'AccountController@resources']);

    Route::get('script', [
        'as' => 'account.script', 'uses' => 'AccountController@script']);

    Route::get('uploads', [
        'as' => 'account.uploads', 'uses' => 'AccountController@uploads']);

    Route::get('resetkey', [
        'as' => 'account.resetkey', 'uses' => 'AccountController@resetKey']);
});

/*
 * Routes only admins can access
 */
Route::group(['middleware' => 'admin', 'prefix' => 'a'], function () {
    Route::get('/', [
        'as' => 'admin', 'uses' => 'AdminController@index']);

    Route::get('requests', [
        'as' => 'admin.requests', 'uses' => 'AdminController@requests']);

    Route::get('users', [
        'as' => 'admin.users', 'uses' => 'AdminController@users']);

    Route::get('users/ban/{user}', [
        'as' => 'admin.users.ban', 'uses' => 'AdminController@ban']);

    Route::get('users/unban/{user}', [
        'as' => 'admin.users.unban', 'uses' => 'AdminController@unban']);

    Route::get('users/enable/{user}', [
        'as' => 'admin.users.enable', 'uses' => 'AdminController@enable']);
});

/*
 * API routes
 * TODO: Write an API middleware to authenticate based on API key
 */
Route::group(['middleware' => 'api', 'prefix' => 'api'], function () {
    Route::post('upload', [
        'as' => 'api.upload', 'uses' => 'ApiController@upload']);
});