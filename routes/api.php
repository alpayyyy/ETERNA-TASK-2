<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\FeaturedPostController;

// Herkese açık rotalar (Giriş yapmadan erişilebilenler)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Kategorileri listeleme herkese açık
Route::get('/categories', [CategoryController::class, 'index']); 

// Öne Çıkan Yazılar (Herkese açık ve özel algoritmalı)
Route::get('/featured-posts', [FeaturedPostController::class, 'index']);

// Blog yazılarını listeleme ve tekil okuma herkese açık
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post}', [PostController::class, 'show']);

// Sadece giriş yapmış kullanıcıların erişebileceği rotalar (Sanctum Middleware ile korunur)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Kategori ekleme, düzenleme ve silme işlemleri
    Route::apiResource('categories', CategoryController::class)->except(['index']);

    // Blog yazısı oluşturma, düzenleme ve silme işlemleri (Giriş zorunlu)
    Route::apiResource('posts', PostController::class)->except(['index', 'show']);

    // Yorum İşlemleri
    Route::post('/posts/{post}/comments', [CommentController::class, 'store']);
    Route::patch('/comments/{comment}/approve', [CommentController::class, 'approve']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
});