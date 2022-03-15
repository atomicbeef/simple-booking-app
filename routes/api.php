<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AppointmentsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('add', [AppointmentsController::class, 'store']);
Route::get('appointments', [AppointmentsController::class, 'show']);
Route::patch('update', [AppointmentsController::class, 'update']);
Route::delete('delete', [AppointmentsController::class, 'delete']);