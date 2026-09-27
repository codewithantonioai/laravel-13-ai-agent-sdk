<?php

use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\TokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [TokenController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('notes', NoteController::class)
        ->names('api.notes')
        ->only(['index', 'show'])
        ->middleware('abilities:notes:read');

    Route::apiResource('notes', NoteController::class)
        ->names('api.notes')
        ->only(['store', 'update', 'destroy'])
        ->middleware('abilities:notes:write');

    Route::delete('/logout', [TokenController::class, 'destroy']);
});
