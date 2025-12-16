<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mascota;
use App\Models\Cita;
use App\Models\Veterinario;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    // ESTADÍSTICAS DEL DASHBOARD
    public function estadisticas()
    {
        return response()->json([
            'citas_hoy' => $this->contarCitasHoy(),
            'mascotas_total' => Mascota::count(),
            'clientes_activos' => User::where('rol', 'cliente')->count(),
            'veterinarios' => Veterinario::count()
        ]);
    }

    private function contarCitasHoy()
    {
        return Cita::whereDate('fecha', Carbon::today())->count();
    }

    // GESTIONAR CITAS - ADMIN
    public function citasAdmin()
    {
        try {
            // Obtener citas con todas sus relaciones
            $citas = Cita::with(['user', 'mascota', 'veterinario', 'servicio'])
                ->orderBy('fecha', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'count' => count($citas),
                'data' => $citas
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en citasAdmin: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Error al cargar citas'
            ], 500);
        }
    }

    public function filtrarCitas(Request $request)
    {
        $query = Cita::with(['user', 'mascota']);

        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('veterinario')) {
            $query->where('veterinario', $request->veterinario);
        }

        if ($request->filled('servicio')) {
            $query->where('servicio', $request->servicio);
        }

        $citas = $query->orderBy('fecha', 'desc')->get();

        return response()->json(['data' => $citas]);
    }

    public function actualizarCita(Request $request, $id)
    {
        $cita = Cita::findOrFail($id);

        $validated = $request->validate([
            'servicio' => 'string|max:255',
            'veterinario' => 'string|max:255',
            'fecha' => 'date',
            'hora' => 'string',
            'motivo' => 'string',
            'notas' => 'string|nullable',
            'status' => 'string|in:Próxima,Completada,Agendada,Cancelada'
        ]);

        $cita->update($validated);

        return response()->json([
            'message' => 'Cita actualizada correctamente',
            'cita' => $cita
        ]);
    }

    public function cancelarCita($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->update(['status' => 'Cancelada']);

        return response()->json(['message' => 'Cita cancelada']);
    }

    public function aceptarCita($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->update(['status' => 'Próxima']);

        return response()->json(['message' => 'Cita aceptada', 'cita' => $cita]);
    }

    public function rechazarCita($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->update(['status' => 'Cancelada']);

        return response()->json(['message' => 'Cita rechazada', 'cita' => $cita]);
    }

    // MASCOTAS - ADMIN
    public function mascotasAdmin()
    {
        $mascotas = Mascota::with(['user' => function ($query) {
            $query->select('id', 'name', 'email');
        }])
            ->orderBy('nombre')
            ->get();

        // Las rutas ya vienen como /uploads/mascotas/... que es lo correcto
        // No necesitamos modificarlas porque Laravel sirve desde public/

        return response()->json(['data' => $mascotas]);
    }

    public function buscarMascotas(Request $request)
    {
        $query = Mascota::with(['user' => function ($q) {
            $q->select('id', 'name', 'email');
        }]);

        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        if ($request->filled('especie')) {
            $query->where('especie', $request->especie);
        }

        if ($request->filled('propietario')) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . request('propietario') . '%');
            });
        }

        $mascotas = $query->orderBy('nombre')->get();

        // Las rutas ya vienen como /uploads/mascotas/... que es lo correcto
        // No necesitamos modificarlas porque Laravel sirve desde public/

        return response()->json(['data' => $mascotas]);
    }

    // VETERINARIOS - ADMIN CRUD
    public function veterinariosAdmin()
    {
        $veterinarios = Veterinario::orderBy('nombre')->get();
        return response()->json(['data' => $veterinarios]);
    }

    public function crearVeterinario(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'especialidad' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'horario' => 'nullable|string'
        ]);

        $veterinario = Veterinario::create($validated);

        return response()->json([
            'message' => 'Veterinario creado exitosamente',
            'veterinario' => $veterinario
        ], 201);
    }

    public function actualizarVeterinario(Request $request, $id)
    {
        $veterinario = Veterinario::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'string|max:255',
            'especialidad' => 'string|max:255',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'horario' => 'nullable|string'
        ]);

        $veterinario->update($validated);

        return response()->json([
            'message' => 'Veterinario actualizado',
            'veterinario' => $veterinario
        ]);
    }

    public function eliminarVeterinario($id)
    {
        $veterinario = Veterinario::findOrFail($id);
        $veterinario->delete();

        return response()->json(['message' => 'Veterinario eliminado']);
    }

    // REPORTES
    public function reportesOcupacion()
    {
        $ocupacion = Cita::select('veterinario')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN status = "Completada" THEN 1 ELSE 0 END) as completadas')
            ->groupBy('veterinario')
            ->get();

        return response()->json($ocupacion);
    }

    public function reportesServicios()
    {
        $servicios = Cita::select('servicio')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('servicio')
            ->orderBy('total', 'desc')
            ->get();

        return response()->json($servicios);
    }

    public function reportesMensual()
    {
        $citas_mes = Cita::selectRaw('DATE(fecha) as fecha')
            ->selectRaw('COUNT(*) as total')
            ->whereYear('fecha', Carbon::now()->year)
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        return response()->json($citas_mes);
    }

    public function reportesEstadisticas()
    {
        return response()->json([
            'citas_mes' => Cita::whereMonth('fecha', Carbon::now()->month)->count(),
            'citas_completadas' => Cita::where('status', 'Completada')->count(),
            'clientes_totales' => User::where('rol', 'cliente')->count(),
            'mascotas_totales' => Mascota::count(),
            'servicios_ofrecidos' => Cita::distinct('servicio')->count('servicio'),
            'veterinarios_activos' => Veterinario::count(),
            'citas_promedio_diario' => round(Cita::count() / (Carbon::now()->diffInDays(Carbon::create(2025, 1, 1)) ?: 1), 2)
        ]);
    }
}
