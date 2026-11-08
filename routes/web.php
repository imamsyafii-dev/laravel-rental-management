<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::view('/admin/categories', 'admin.categories')
    ->middleware('auth')
    ->name('admin.categories');

Route::view('/admin/items', 'admin.items')
    ->middleware('auth')
    ->name('admin.items');

require __DIR__.'/auth.php';
