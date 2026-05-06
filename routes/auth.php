<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    // Volt es un paquete de Livewire que permite crear rutas para componentes de Livewire de forma sencilla, 
    // en este caso se están creando rutas para los componentes de autenticación (login, register, forgot-password, reset-password) 
    // y se les asigna un nombre a cada ruta para poder referenciarlas fácilmente en el código.
    // En lugar de usar las rutas tradicionales de Laravel Breeze, se están utilizando rutas de Volt para manejar la autenticación
    // Un componente Volt une la lógica de Livewire con la vista en un mismo archivo. Estos se localizan en resources/views/livewire/auth, 
    // y cada uno de ellos maneja una parte del proceso de autenticación (login, register, forgot-password, reset-password).
    Volt::route('login', 'auth.login')
        ->name('login');

    Volt::route('register', 'auth.register')
        ->name('register');

    Volt::route('forgot-password', 'auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'auth.reset-password')
        ->name('password.reset');

});

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'auth.confirm-password')
        ->name('password.confirm');
});

Route::post('logout', App\Livewire\Actions\Logout::class)
    ->name('logout');
