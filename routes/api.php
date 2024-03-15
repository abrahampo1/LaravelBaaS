<?php

use App\Http\Controllers\ColumnController;
use App\Http\Controllers\TableController;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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



//add middleware group to auth 
// Route::middleware('auth:sanctum')->group(function () {
//     Route::apiResource('table', TableController::class);
// });

Route::apiResource('table', TableController::class);
Route::post('column/create/{table}', [ColumnController::class, 'store']);
Route::apiResource('column', ColumnController::class);
