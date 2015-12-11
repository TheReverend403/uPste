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

    Route::get('password/email', [
        'as' => 'account.password.email', 'uses' => 'Auth\PasswordController@getEmail']);

    Route::post('password/email', [
        'as' => 'account.password.email', 'uses' => 'Auth\PasswordController@postEmail']);

    Route::get('password/reset/{token}', [
        'as' => 'account.password.reset', 'uses' => 'Auth\PasswordController@getReset']);

    Route::post('password/reset', [
        'as' => 'account.password.reset', 'uses' => 'Auth\PasswordController@postReset']);
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

    Route::get('resources/scripts/bash', [
        'as' => 'account.resources.bash', 'uses' => 'AccountController@bashScript']);

    Route::get('uploads', [
        'as' => 'account.uploads', 'uses' => 'AccountController@uploads']);

    Route::post('resetkey', [
        'as' => 'account.resetkey', 'uses' => 'AccountController@postResetKey']);

    Route::get('resetkey', [
        'as' => 'account.resetkey', 'uses' => 'AccountController@getResetKey']);
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

    Route::get('users/{user}/ban', [
        'as' => 'admin.users.ban', 'uses' => 'AdminController@ban']);

    Route::get('users/{user}/unban', [
        'as' => 'admin.users.unban', 'uses' => 'AdminController@unban']);

    Route::get('users/{user}/uploads', [
        'as' => 'admin.users.uploads', 'uses' => 'AdminController@uploads']);

    Route::get('users/{user}/delete', [
        'as' => 'admin.users.delete', 'uses' => 'AdminController@delete']);

    Route::get('users/{user}/accept', [
        'as' => 'admin.users.accept', 'uses' => 'AdminController@accept']);

    Route::get('users/{user}/reject', [
        'as' => 'admin.users.reject', 'uses' => 'AdminController@reject']);
});

/*
 * API routes
 */
Route::group(['middleware' => 'api', 'prefix' => 'api'], function () {
    Route::post('upload', [
        'as' => 'api.upload', 'uses' => 'ApiController@upload']);
});