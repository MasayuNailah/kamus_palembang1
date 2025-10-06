<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KataController;
use App\Http\Controllers\KalimatController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/beranda', [KataController::class, 'getKata']);

Route::get('/EntriKata', [KataController::class, 'tambahKata']);
Route::post('/EntriKata', [KataController::class, 'store'])->name('kata.store');

Route::get('/EntriKalimat', [KalimatController::class, 'tambahKalimat']);
Route::post('/EntriKalimat', [KalimatController::class, 'store']);

Route::get('/ValidasiKata', [KataController::class, 'validasiList']);
Route::post('/ValidasiKata/{id_kata}/validate', [KataController::class, 'validateKata'])->name('kata.validate');

// Detail & edit kata
Route::get('/DetailKata/{id_kata}', [KataController::class, 'show'])->name('detail.kata');
Route::post('/DetailKata/{id_kata}', [KataController::class, 'update'])->name('detail.kata.update');

Route::get('/ValidasiKalimat', [KalimatController::class, 'validasiList']);
Route::post('/ValidasiKalimat/{id_kalimat}/validate', [KalimatController::class, 'validateKalimat']);

// Detail & edit kalimat
Route::get('/DetailKalimat/{id_kalimat}', [KalimatController::class, 'show'])->name('detail.kalimat');
Route::post('/DetailKalimat/{id_kalimat}', [KalimatController::class, 'update'])->name('detail.kalimat.update');

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

// User management routes (Controller)
Route::get('/KelolaUser', [UserController::class, 'index']);
Route::get('/TambahUser', [UserController::class, 'create']);
Route::post('/TambahUser', [UserController::class, 'store']);
Route::get('/EditUser/{id_user}', [UserController::class, 'edit']);
Route::post('/EditUser/{id_user}', [UserController::class, 'update']);
Route::delete('/EditUser/{id_user}', [UserController::class, 'destroy']);

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
