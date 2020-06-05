<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With, GoogleDataStudio');
header('Access-Control-Allow-Credentials: true');

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
    'middleware' => 'jwt.auth',
    'namespace' => 'App\Modules\User\Controllers'
], function() {

    Route::get('settings/get', 'UserProfileController@getProfileByLoggedUser');

});
