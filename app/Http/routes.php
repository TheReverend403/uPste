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
        'as' => 'index', 'uses' => 'IndexController@getIndex']);

    Route::get('login', [
        'as' => 'login', 'uses' => 'Auth\AuthController@getLogin']);

    Route::post('login', [
        'as' => 'login', 'uses' => 'Auth\AuthController@postLogin']);

    Route::get('register', [
        'as' => 'register', 'uses' => 'Auth\AuthController@getRegister']);

    Route::post('register', [
        'as' => 'register', 'uses' => 'Auth\AuthController@postRegister']);

    Route::group(['prefix' => 'password'], function () {
        Route::get('email', [
            'as' => 'account.password.email', 'uses' => 'Auth\PasswordController@getEmail']);

        Route::post('email', [
            'as' => 'account.password.email', 'uses' => 'Auth\PasswordController@postEmail']);

        Route::get('reset/{token}', [
            'as' => 'account.password.reset', 'uses' => 'Auth\PasswordController@getReset']);

        Route::post('reset', [
            'as' => 'account.password.reset', 'uses' => 'Auth\PasswordController@postReset']);
    });
});

/*
 * Routes only authenticated users can access
 */
Route::group(['middleware' => 'auth', 'prefix' => 'u'], function () {
    Route::get('/', [
        'as' => 'account', 'uses' => 'AccountController@getindex']);

    Route::get('logout', [
        'as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);

    Route::get('resources', [
        'as' => 'account.resources', 'uses' => 'AccountController@getResources']);

    Route::get('resources/scripts/bash', [
        'as' => 'account.resources.bash', 'uses' => 'AccountController@getBashScript']);

    Route::group(['prefix' => 'uploads'], function () {
        Route::get('/', [
            'as' => 'account.uploads', 'uses' => 'AccountController@getUploads']);

        Route::get('{upload}/delete', [
            'as' => 'account.uploads.delete', 'uses' => 'Controller@getNotAllowed']);

        Route::post('{upload}/delete', [
            'as' => 'account.uploads.delete', 'uses' => 'AccountController@postUploadsDelete']);
    });

    Route::post('resetkey', [
        'as' => 'account.resetkey', 'uses' => 'AccountController@postResetKey']);

    Route::get('resetkey', [
        'as' => 'account.resetkey', 'uses' => 'AccountController@getNotAllowed']);
});

/*
 * Routes only admins can access
 */
Route::group(['middleware' => 'admin', 'prefix' => 'a'], function () {

    Route::get('/', [
        'as' => 'admin', 'uses' => 'AdminController@getIndex']);

    Route::get('requests', [
        'as' => 'admin.requests', 'uses' => 'AdminController@getRequests']);

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [
            'as' => 'admin.users', 'uses' => 'AdminController@getUsers']);

        Route::post('{user}/ban', [
            'as' => 'admin.users.ban', 'uses' => 'AdminController@postUserBan']);

        Route::post('{user}/unban', [
            'as' => 'admin.users.unban', 'uses' => 'AdminController@postUserUnban']);

        Route::get('{user}/uploads', [
            'as' => 'admin.users.uploads', 'uses' => 'AdminController@getUploads']);

        Route::post('uploads/{upload}/delete', [
            'as' => 'admin.uploads.delete', 'uses' => 'AdminController@postUploadsDelete']);

        Route::post('{user}/delete', [
            'as' => 'admin.users.delete', 'uses' => 'AdminController@postUserDelete']);

        Route::post('{user}/accept', [
            'as' => 'admin.users.accept', 'uses' => 'AdminController@postUserAccept']);

        Route::post('{user}/reject', [
            'as' => 'admin.users.reject', 'uses' => 'AdminController@postUserReject']);
    });
});

/*
 * API routes
 */
Route::group(['middleware' => 'api', 'prefix' => 'api'], function () {
    Route::post('upload', [
        'as' => 'api.upload', 'uses' => 'ApiController@postUpload']);

    Route::get('upload', [
        'as' => 'api.upload', 'uses' => 'Controller@getNotAllowed']);
});