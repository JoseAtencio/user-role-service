<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Service\UserController;
use App\Http\Controllers\Service\NotifyController;
use App\Http\Controllers\Service\EnterpriseController;
use App\Http\Controllers\Service\ActivityController;
use App\Http\Controllers\Service\CurrencyConverterController;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;

$SERVER_VERSION = 'v1';

Route::group(['prefix' => $SERVER_VERSION . '/auth'], function () {
    Route::post('sign-in', [AuthController::class, 'login']); 
    Route::post('sign-up', [AuthController::class, 'register']);
});

Route::group(['middleware' => ['auth:sanctum', 'check-permissions']], function () use ($SERVER_VERSION) {
    Route::group(['prefix' => $SERVER_VERSION ], function () {
        Route::post('/convert', [CurrencyConverterController::class, 'convert'])->name('convert');
    });
    
    
    JsonApiRoute::server($SERVER_VERSION)->prefix($SERVER_VERSION)->resources(function ($server) {
        $server->resource('users', UserController::class)->only('index','show')->actions(function ($actions) {
            $actions->post('{user}', 'update');
            $actions->delete('{user}', 'destroy');
        });
        $server->resource('notifies', NotifyController::class)->only('index')->actions(function ($actions) {
            $actions->post('send', 'store');
        });
        $server->resource('enterprises', EnterpriseController::class)->only('index','show','store')->actions(function ($actions) {
            $actions->post('{enterprises}', 'update');
            $actions->delete('{enterprises}', 'destroy');
            $actions->post('{enterprises}/add_activity', 'add_activities');
            $actions->post('{enterprises}/remove_activity', 'remove_activities');
        });
        $server->resource('activities', ActivityController::class)->only('index','show','store')->actions(function ($actions) {
            $actions->post('{activity}', 'update');
            $actions->delete('{activity}', 'destroy');
        });
    });
});

