<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PostController;
use Illuminate\Support\Facades\Route; 

// Para que funcione un nuevo archivo de rutas como es el caso de admin.php, no es suficiente con crearlo, sino que hay que registrarla, 
// bien en el archivo de rutas web.php con require __DIR__.'/admin.php' o en el archivo de bootstrap/app.php
// En ese mismo achivo he definido prefijo para las URI y nombre para las rutas de admin.php (me ahorro escribir el prefijo en cada ruta).

Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');

Route::resource('categories', CategoryController::class);

Route::resource('posts', PostController::class);