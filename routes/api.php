<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Models\Post;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
     Route::get('/posts', [PostController::class, 'index']);
     Route::get('/post/{id}', [PostController::class, 'show']);
     Route::put('/post/{id}', [PostController::class, 'update']);
     Route::post('/post/create', [PostController::class, 'store']);
     Route::delete('/post/{id}', [PostController::class, 'destroy']);
