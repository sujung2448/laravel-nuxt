<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\DebitController;
use App\Http\Controllers\BoardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', [LoginController::class, 'logout']);

    Route::get('user', [UserController::class, 'current']);

    Route::patch('settings/profile', [ProfileController::class, 'update']);
    Route::patch('settings/password', [PasswordController::class, 'update']);

    Route::get('credit/list', [CreditController::class, 'list']);
    Route::patch('credit/store', [CreditController::class, 'store']);
    Route::post('credit/delete', [CreditController::class, 'delete']); //개별삭제
    Route::post('credit/alldelete', [CreditController::class, 'allDelete']); //선택삭제

    Route::get('debit/list', [DebitController::class, 'list']);
    Route::patch('debit/store', [DebitController::class, 'store']);
    Route::post('debit/delete', [DebitController::class, 'delete']);
    Route::post('debit/alldelete', [DebitController::class, 'allDelete']);
    
    Route::get('board/list', [BoardController::class, 'list']);
    Route::post('board/store', [BoardController::class, 'store']);
    Route::get('board/show/{id}', [BoardController::class, 'show']);
    Route::delete('board/{id}', [BoardController::class, 'destroy']);
    Route::get('board/edit/{id}', [BoardController::class, 'edit']);
    Route::put('board/{id}', [BoardController::class, 'update']);




});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);

    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('password/reset', [ResetPasswordController::class, 'reset']);

    Route::post('email/verify/{user}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('email/resend', [VerificationController::class, 'resend']);

    Route::post('oauth/{driver}', [OAuthController::class, 'redirect']);
    Route::get('oauth/{driver}/callback', [OAuthController::class, 'handleCallback'])->name('oauth.callback');
});
