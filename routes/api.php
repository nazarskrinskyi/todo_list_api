<?php

use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

});

Route::group([
    'prefix' => 'tasks'
], function () {
    Route::get('/', [TaskController::class, 'index']);
    Route::post('/store', [TaskController::class, 'store']);
    Route::put('/{id}', [TaskController::class, 'update']);
    Route::put('/{id}/done', [TaskController::class, 'markAsDone']);
    Route::delete('/{id}', [TaskController::class, 'destroy']);
});
