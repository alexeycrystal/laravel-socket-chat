<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


if(!headers_sent()) {
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
    header('Access-Control-Allow-Credentials: true');
}


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
*/

Route::group([
    'prefix' => 'auth',
    'namespace' => 'App\Modules\Auth\Controllers'
], function() {

    Route::post('login', 'LoginController@login');

    Route::post('registration', 'RegistrationController@registration');

});

Route::group([
    'prefix' => 'profile',
    'middleware' => ['jwt.validate'],
    'namespace' => 'App\Modules\User\Controllers'
], function() {

    Route::get('settings/get', 'UserProfileController@getProfileByLoggedUser');

    Route::post('settings/update', 'UserProfileController@updateProfileSettings');

    Route::get('password/change', 'UserProfileController@updatePassword');

});

Route::group([
    'prefix' => 'user',
    'middleware' => 'jwt.validate',
], function() {

    Route::apiResources(
        ['chats' => 'App\Modules\Chat\Controllers\ChatController'],
        ['except' => ['update']]
    );

    Route::apiResources(
        ['contacts' => 'App\Modules\User\Controllers\UserContactController']
    );

    Route::apiResources(
        ['messages' => 'App\Modules\Message\Controllers\MessageController']
    );

    Route::apiResources(
        ['ws/dependencies' => 'App\Modules\Realtime\Controllers\UserRealtimeDependencyController'],
        ['only' => ['store', 'destroy']]
    );
});
