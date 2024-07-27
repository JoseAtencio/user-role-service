<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Service\UserController;

    $SERVER_VERSION = 'v1';
    
    Route::group(['prefix' => $SERVER_VERSION . '/auth'], function () {
        Route::post('sign-in', [AuthController::class, 'login']); 
        Route::post('sign-up', [AuthController::class, 'register']);
    });
    
    // Rutas JSON:API protegidas por middleware de autorización
    Route::group(['middleware' => ['authorization']], function () use ($SERVER_VERSION) {
        JsonApiRoute::server($SERVER_VERSION)->prefix($SERVER_VERSION)->resources(function ($server) {
            $server->resource('users', UserController::class)->only('index', 'store', 'update', 'destroy', 'show');
        });
    });
    


