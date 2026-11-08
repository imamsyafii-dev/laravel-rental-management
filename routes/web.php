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

Route::view('/rentals/create', 'rentals.create')
    ->middleware('auth')
    ->name('rentals.create');

Route::view('/rentals', 'rentals.index')
    ->middleware('auth')
    ->name('rentals.index');

Route::view('/admin/rentals', 'admin.rentals')
    ->middleware('auth')
    ->name('admin.rentals');

require __DIR__.'/auth.php';
