<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NoteCategoryController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\NoteTagController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Notes
    Route::apiResource('notes', NoteController::class);
    Route::post('notes/{id}/restore', [NoteController::class, 'restore']);
    Route::delete('notes/{id}/force-delete', [NoteController::class, 'forceDelete']);
    Route::apiResource('note-categories', NoteCategoryController::class);
    Route::apiResource('note-tags', NoteTagController::class);
});
