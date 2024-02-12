<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('livewire.products.component');
})->name('dashboard');
