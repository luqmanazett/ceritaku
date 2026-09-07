<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TemplateController;
use App\Http\Controllers\EditorController;

Route::get('/', function () {
    // Ambil kategori yang memiliki template aktif, lalu muat 1 template terbaru
    $categories = \App\Models\TemplateCategory::whereHas('templates', function($q) {
        $q->where('is_active', true);
    })->with(['templates' => function($q) {
        $q->where('is_active', true)->latest()->limit(1);
    }])->take(6)->get();
    
    return view('welcome', compact('categories'));
});

Route::get('/template-cover', [TemplateController::class, 'index']);
Route::get('/editor', [EditorController::class, 'index']);
Route::get('/preview', [EditorController::class, 'preview']);
Route::post('/editor/upload-asset', [EditorController::class, 'uploadAsset']);
Route::post('/editor/save', [EditorController::class, 'saveDesign']);
Route::post('/editor/order', [EditorController::class, 'submitOrder']);
Route::get('/media/{type}/{filename}', [EditorController::class, 'serveMedia']);
Route::get('/lacak', [EditorController::class, 'trackOrder']);

Route::get('/bantuan', function () {
    return view('bantuan');
});

use App\Http\Controllers\AdminController;

Route::get('/admin/login', [AdminController::class, 'showLogin']);
Route::post('/admin/login', [AdminController::class, 'processLogin']);
Route::post('/admin/logout', [AdminController::class, 'logout']);

Route::prefix('admin')->middleware(\App\Http\Middleware\AdminAuth::class)->group(function () {
    Route::get('/', [AdminController::class, 'dashboard']);
    Route::get('/pesanan', [AdminController::class, 'orders']);
    Route::post('/pesanan/{id}/status', [AdminController::class, 'updateOrderStatus']);
    Route::post('/pesanan/{id}/cancel', [AdminController::class, 'cancelOrder']);
    Route::get('/pesanan/{design_code}/produksi', [AdminController::class, 'produksiView']);
    Route::get('/pesanan/{design_code}/download-photos', [AdminController::class, 'downloadPhotos']);
    Route::get('/pesanan/{design_code}/download-json', [AdminController::class, 'downloadJson']);
    Route::get('/template', [AdminController::class, 'templateIndex']);
    Route::get('/template/create', [AdminController::class, 'templateCreate']);
    Route::post('/template', [AdminController::class, 'templateStore']);
    Route::post('/template/dynamic', [AdminController::class, 'templateStoreDynamic']);
    Route::get('/template/{id}/editor', [AdminController::class, 'templateEditor']);
    Route::post('/template/{id}/save', [AdminController::class, 'templateSaveLayout']);
    Route::put('/template/{id}', [AdminController::class, 'templateUpdateInfo']);
    Route::delete('/template/{id}', [AdminController::class, 'templateDelete']);
    Route::get('/kategori', [AdminController::class, 'categoryIndex']);
    Route::post('/kategori', [AdminController::class, 'categoryStore']);
    Route::put('/kategori/{id}', [AdminController::class, 'categoryUpdate']);
    Route::delete('/kategori/{id}', [AdminController::class, 'categoryDelete']);
    
    Route::get('/pengaturan', [AdminController::class, 'settingsIndex']);
    Route::post('/pengaturan', [AdminController::class, 'settingsUpdate']);
});
