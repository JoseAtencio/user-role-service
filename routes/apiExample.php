<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Service\UserController;
use App\Http\Controllers\Service\NotifyController;
use App\Http\Controllers\Service\EnterpriseController;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;

$SERVER_VERSION = 'v1';
/*
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
        $server->resource('enterprises', EnterpriseController::class)->only('index','show');
    });
});
*/

Route::group(['prefix' => $SERVER_VERSION . '/auth'], function () {
    Route::post('sign-in', [AuthController::class, 'login'])->name('auth.sign-in'); 
    Route::post('sign-up', [AuthController::class, 'register'])->name('auth.sign-up');
});

// Rutas protegidas con middleware
Route::group(['middleware' => ['auth:sanctum', 'check-permissions']], function () use ($SERVER_VERSION) {
    // Rutas de usuarios
    Route::group(['prefix' => $SERVER_VERSION . '/users'], function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('{user}', [UserController::class, 'show'])->name('users.show');
        Route::put('{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // Rutas de notificaciones
    Route::group(['prefix' => $SERVER_VERSION . '/notifies'], function () {
        Route::get('/', [NotifyController::class, 'index'])->name('notifies.index');
        Route::post('send', [NotifyController::class, 'store'])->name('notifies.send');
    });

    // Rutas de empresas
    Route::group(['prefix' => $SERVER_VERSION . '/enterprises'], function () {
        Route::get('/', [EnterpriseController::class, 'index'])->name('enterprises.index');
        Route::post('/', [EnterpriseController::class, 'store'])->name('enterprises.store');
        Route::get('{enterprise}', [EnterpriseController::class, 'show'])->name('enterprises.show');
    });
});


