<?php

use Illuminate\Support\Facades\Route;

Route::get('contact/{slug?}', App\Http\Livewire\Web\Contact::class)->name('contact');
Route::get('/', App\Http\Livewire\Web\Home::class)->name('home');
