<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\VeterinarioController;
use App\Http\Controllers\HistorialMedicoController;
use App\Http\Controllers\ConfiguracionClinicaController;
use App\Http\Controllers\AdminController;
use App\Models\Cita;

// ============== RUTAS PÚBLICAS ==============

// Autenticación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Información pública
Route::get('/servicios', [ServicioController::class, 'index']);
Route::get('/servicios/{id}', [ServicioController::class, 'show']);
Route::get('/veterinarios', [VeterinarioController::class, 'index']);
Route::get('/veterinarios/{id}', [VeterinarioController::class, 'show']);
Route::get('/clinica/info', [ConfiguracionClinicaController::class, 'show']);

// DEBUG - Ver todas las citas sin autenticación (SOLO PARA PRUEBAS)
Route::get('/debug/citas', function () {
    return response()->json([
        'count' => Cita::count(),
        'data' => Cita::with(['user', 'mascota'])->get()
    ]);
});

// ============== RUTAS PROTEGIDAS ==============

Route::middleware('auth:sanctum')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // MASCOTAS - Usuario
    Route::get('/mascotas', [MascotaController::class, 'index']);
    Route::get('/mascotas/{id}', [MascotaController::class, 'show']);
    Route::post('/mascotas', [MascotaController::class, 'store']);
    Route::put('/mascotas/{id}', [MascotaController::class, 'update']);
    Route::delete('/mascotas/{id}', [MascotaController::class, 'destroy']);

    // CITAS - Usuario
    Route::get('/citas', [CitaController::class, 'index']);
    Route::get('/citas/{id}', [CitaController::class, 'show']);
    Route::post('/citas', [CitaController::class, 'store']);
    Route::put('/citas/{id}', [CitaController::class, 'update']);
    Route::delete('/citas/{id}', [CitaController::class, 'destroy']);
    Route::post('/horarios-disponibles', [CitaController::class, 'horariosDisponibles']);

    // HISTORIAL MÉDICO - Usuario
    Route::get('/mascotas/{mascotaId}/historial', [HistorialMedicoController::class, 'index']);

    // ============== RUTAS ADMIN ==============
    Route::middleware('IsAdmin:admin')->group(function () {
        // SERVICIOS - Admin
        Route::post('/admin/servicios', [ServicioController::class, 'store']);
        Route::put('/admin/servicios/{id}', [ServicioController::class, 'update']);
        Route::delete('/admin/servicios/{id}', [ServicioController::class, 'destroy']);

        // VETERINARIOS - Admin
        Route::post('/admin/veterinarios', [VeterinarioController::class, 'store']);
        Route::put('/admin/veterinarios/{id}', [VeterinarioController::class, 'update']);
        Route::delete('/admin/veterinarios/{id}', [VeterinarioController::class, 'destroy']);

        // MASCOTAS - Admin (ver todas)
        Route::get('/admin/mascotas', [MascotaController::class, 'index']);
        Route::delete('/admin/mascotas/{id}', [MascotaController::class, 'destroy']);

        // HISTORIAL MÉDICO - Admin
        Route::post('/admin/historial', [HistorialMedicoController::class, 'store']);

        // CONFIGURACIÓN CLÍNICA
        Route::put('/admin/clinica/config', [ConfiguracionClinicaController::class, 'update']);

        // ESTADÍSTICAS - Admin
        Route::get('/admin/estadisticas', [AdminController::class, 'estadisticas']);

        // CITAS - Admin
        Route::get('/admin/citas', [AdminController::class, 'citasAdmin']);
        Route::post('/admin/citas/filtrar', [AdminController::class, 'filtrarCitas']);
        Route::put('/admin/citas/{id}', [AdminController::class, 'actualizarCita']);
        Route::post('/admin/citas/{id}/cancelar', [AdminController::class, 'cancelarCita']);
        Route::post('/admin/citas/{id}/aceptar', [AdminController::class, 'aceptarCita']);
        Route::post('/admin/citas/{id}/rechazar', [AdminController::class, 'rechazarCita']);

        // MASCOTAS - Admin (global)
        Route::get('/admin/mascotas-global', [AdminController::class, 'mascotasAdmin']);
        Route::post('/admin/mascotas/buscar', [AdminController::class, 'buscarMascotas']);

        // VETERINARIOS - Admin
        Route::get('/admin/veterinarios-list', [AdminController::class, 'veterinariosAdmin']);
        Route::post('/admin/veterinarios-crear', [AdminController::class, 'crearVeterinario']);
        Route::put('/admin/veterinarios/{id}', [AdminController::class, 'actualizarVeterinario']);
        Route::delete('/admin/veterinarios/{id}', [AdminController::class, 'eliminarVeterinario']);

        // REPORTES - Admin
        Route::get('/admin/reportes/ocupacion', [AdminController::class, 'reportesOcupacion']);
        Route::get('/admin/reportes/servicios', [AdminController::class, 'reportesServicios']);
        Route::get('/admin/reportes/mensual', [AdminController::class, 'reportesMensual']);
        Route::get('/admin/reportes/estadisticas', [AdminController::class, 'reportesEstadisticas']);
    });
});
