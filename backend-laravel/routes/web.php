<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return file_get_contents(public_path('index.html'));
});

// Rutas para archivos HTML que sirven como "páginas"
Route::get('/login', function () {
    return file_get_contents(public_path('login.html'));
});

Route::get('/mascotas', function () {
    return file_get_contents(public_path('mascotas.html'));
});

Route::get('/citas', function () {
    return file_get_contents(public_path('citas.html'));
});

Route::get('/historial', function () {
    return file_get_contents(public_path('historial.html'));
});

Route::get('/contacto', function () {
    return file_get_contents(public_path('contacto.html'));
});

Route::get('/admin', function () {
    return file_get_contents(public_path('indexadmin.html'));
});

Route::get('/crud', function () {
    return file_get_contents(public_path('crud.html'));
});

Route::get('/carrito', function () {
    return file_get_contents(public_path('carrito.html'));
});
