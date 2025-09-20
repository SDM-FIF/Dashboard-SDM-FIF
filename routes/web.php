<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// ============================
// Auth Routes
// ============================
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login')
    ->middleware('guest'); // hanya bisa diakses kalau belum login

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process')
    ->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth'); // hanya bisa logout kalau sudah login

// ============================
// Landing & Welcome
// ============================
Route::get('/', function () {
    return view('landingpage');
})->name('landingpage');

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// ============================
// Protected Routes (harus login)
// ============================
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Dashboard Dosen
    Route::get('/dashboard-dosen', function () {
        return view('dashboard-dosen');
    })->name('dashboard-dosen');

    // Data Management
    Route::get('/data-dosen', function () {
        return view('data-dosen');
    })->name('data-dosen');

    Route::get('/data-tpa', function () {
        return view('data.tpa');
    })->name('data-tpa');

    Route::get('/dashboard-tpa', function () {
         return view('dashboard-tpa');
    })->name('dashboard-tpa');
    // Kompetisi
    Route::get('/kompetisi', function () {
        return view('kompetisi.index');
    })->name('kompetisi');

    Route::get('dashboard-kompetisi', function () {
        return view('dashboard-kompetisi');
    })->name('dashboard-kompetisi');
    // Management
    Route::get('/kelola-dosen', function () {
        return view('kelola-dosen');
    })->name('kelola-dosen');

    Route::get('/manajemen-tpa', function () {
        return view('manajemen.tpa');
    })->name('manajemen-tpa');

    // Recruitment
    Route::get('/rekrutasi-dosen', function () {
        return view('rekrutasi.dosen');
    })->name('rekrutasi-dosen');

    Route::get('/manajemen-mahasiswa', function () {
        return view('manajemen.mahasiswa');
    })->name('manajemen-mahasiswa');

    // Reports
    Route::get('/master-data', function () {
        return view('reports.master-data');
    })->name('master-data');

    // System
    Route::get('/pengaturan', function () {
        return view('system.pengaturan');
    })->name('pengaturan');
});
