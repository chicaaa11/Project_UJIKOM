<?php 
use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\KategoriController; 
use App\Http\Controllers\API\AlatController; 

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);   

Route::middleware('auth:sanctum')->group(function () {     
    Route::get('/me', [AuthController::class, 'me']);     
    Route::post('/logout', [AuthController::class, 'logout']); 

    Route::middleware('role.admin')->group(function () {              
        Route::apiResource('kategori', KategoriController::class);
        Route::apiResource('alat', AlatController::class); 
        Route::get('/katalog', [AlatController::class, 'katalog']); 
        Route::apiResource('users', UserController::class);
    });     
    
    Route::middleware('role.petugas')->group(function () {              
        // Route untuk hak akses petugas
    });     
    
    Route::middleware('role.peminjam')->group(function () {              
        Route::get('/katalog', [AlatController::class, 'katalog']);
    }); 
});