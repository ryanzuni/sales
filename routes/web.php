<?php

use Illuminate\Support\Facades\Route;
// use App\Livewire\Sales\Index;
use App\Livewire\Dashboard;
use App\Livewire\Sales;
// use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
});

// Volt::route('/sales', 'sales.index');
// Volt::route('/sales', 'sales.page');

Route::get('/dashboard', Dashboard::class)->name('dashboard');
Route::get('/sales', Sales::class)->name('sales');
