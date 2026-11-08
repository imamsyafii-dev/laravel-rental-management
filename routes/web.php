<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::view('/rentals/create', 'rentals.create')
    ->middleware('auth')
    ->name('rentals.create');

Route::view('/rentals', 'rentals.index')
    ->middleware('auth')
    ->name('rentals.index');

Route::view('/admin/categories', 'admin.categories')
    ->middleware(['auth', 'admin'])
    ->name('admin.categories');

Route::view('/admin/items', 'admin.items')
    ->middleware(['auth', 'admin'])
    ->name('admin.items');

Route::view('/admin/rentals', 'admin.rentals')
    ->middleware(['auth', 'admin'])
    ->name('admin.rentals');

require __DIR__.'/auth.php';
