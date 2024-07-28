<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Service\UserController;
use App\Http\Controllers\Service\NotifyController;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;

$SERVER_VERSION = 'v1';

Route::group(['prefix' => $SERVER_VERSION . '/auth'], function () {
    Route::post('sign-in', [AuthController::class, 'login']); 
    Route::post('sign-up', [AuthController::class, 'register']);
});

Route::group(['middleware' => ['auth:sanctum', 'check-permissions']], function () use ($SERVER_VERSION) {
    //Route::put($SERVER_VERSION . '/users/{id}', [UserController::class, 'update']);
    JsonApiRoute::server($SERVER_VERSION)->prefix($SERVER_VERSION)->resources(function ($server) {
        $server->resource('users', UserController::class)->only('index','show')->actions(function ($actions) {
            $actions->post('{user}', 'update');
            $actions->delete('{user}', 'destroy');
        });
        $server->resource('notifies', NotifyController::class)->only('index')->actions(function ($actions) {
            $actions->post('send', 'store');
        });
    });
});

