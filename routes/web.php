<?php

use Gopay\GopayUi\Http\Controllers\GopayController;
use Illuminate\Support\Facades\Route;

Route::post('/gopay/payment/init', [GopayController::class, 'init'])->name('gopay.init');
Route::get('/gopay/payment/check', [GopayController::class, 'check'])->name('gopay.check');
