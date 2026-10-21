<?php

use Illuminate\Http\Request;
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

Route::post('/generate-token', [\App\Http\Controllers\Api\TokenController::class, 'generateToken']);
Route::post('/zip-code', [App\Http\Controllers\Api\ZipCodeController::class, 'zipCodeQuery'])->name('zipcode');
Route::post('/payment/notification', [App\Http\Controllers\Api\PaymentController::class, 'notification'])->name('payment.notification');
Route::get('/payment/check-status/{hash}', [App\Http\Controllers\Api\PaymentController::class, 'checkStatus'])->name('payment.check-status');

/*
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::middleware('auth:sanctum')->group(function() {
        //
    });
*/


