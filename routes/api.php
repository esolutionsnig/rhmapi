<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::post('signin', 'Api\AuthController@signIn');
    Route::post('signup', 'Api\AuthController@signUp');
    Route::get('signout', 'Api\AuthController@signOut');

    Route::group([
        'namespace' => 'Api',
        'middleware' => 'api',
        'prefix' => 'password',
    ], function () {
        Route::post('create', 'PasswordResetController@create');
        Route::get('find/{token}', 'PasswordResetController@find');
        Route::post('reset', 'PasswordResetController@reset');
    });

    // Authenticated Routes
    Route::group(['middleware' => 'auth:api'], function () {
        // Get All Users
        Route::get('users/all', 'Api\AuthController@getUsers');
        // Get single user
        Route::get('users/{id}', 'Api\AuthController@getUser');
        // Change Password
        Route::post('user/change-password', 'Api\AuthController@changePassword');

        // Manage Games
        Route::apiResource('/games', 'GameController');

        // Manage Game Plays
        Route::apiResource('/gameplays', 'GameplayController');
        Route::group(['prefix' => 'gameplays'], function () {
            Route::post('/day', 'GameplayController@getByDate');
            Route::post('/daterange', 'GameplayController@getByDateRange');
            Route::post('/tophundred', 'GameplayController@getTopHundred');
        });
    });
});
