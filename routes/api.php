<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\CommentController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function(){
    Route::post('login','login');
    Route::post('register','register');
});

Route::prefix('feedback')->middleware('auth:sanctum')->group(function () {
    Route::post('/create', [FeedbackController::class, 'create']);
    Route::get('/view/{feedback_id}', [FeedbackController::class, 'view']);
    Route::get('/', [FeedbackController::class, 'index']);
});

Route::prefix('comments')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [CommentController::class, 'index']);
    Route::post('/create', [CommentController::class, 'create']);
});
