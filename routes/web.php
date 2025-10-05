<?php

use Illuminate\Support\Facades\Route;

// Halaman utama web (dashboard AdminLTE)
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');
