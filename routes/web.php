<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Sales\Index;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
});

// Volt::route('/sales', 'sales.index');
Volt::route('/sales', 'sales.page');
