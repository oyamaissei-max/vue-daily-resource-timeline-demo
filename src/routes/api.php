<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ResourceController;
use App\Http\Controllers\Api\ReservationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// リソース一覧取得
Route::get('/resources', [ResourceController::class, 'index']);

// 予約一覧取得（期間指定あり）
Route::get('/reservations', [ReservationController::class, 'index']);