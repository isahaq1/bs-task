<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BsPaymentController;

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


Route::controller(AuthController::class)->group(['middleware' => ['cors']],function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::group(['middleware' => ['cors']], function () {
Route::get('mockresponse', [BsPaymentController::class, 'mockResponse'])->name('mock-response');
Route::post('payment', [BsPaymentController::class, 'payment'])->name('payment');
Route::post('approve.payment', [BsPaymentController::class, 'paymentApproval'])->name('payment-approval');
});