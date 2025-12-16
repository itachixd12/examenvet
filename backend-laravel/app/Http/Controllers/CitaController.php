<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Mascota;
use App\Models\Veterinario;
use App\Models\Servicio;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    // Obtener citas del usuario autenticado
    public function index(Request $request)
    {
        $citas = Cita::where('user_id', $request->user()->id)
            ->with(['mascota', 'veterinario', 'servicio'])
            ->orderBy('fecha', 'desc')
            ->get();

        return response()->json($citas);
    }

    // Obtener una cita específica
    public function show($id)
    {
        $cita = Cita::with(['mascota', 'veterinario', 'servicio'])->find($id);

        if (!$cita) {
            return response()->json(['message' => 'Cita no encontrada'], 404);
        }

        return response()->json($cita);
    }

    // Crear nueva cita
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mascota_id' => 'required|exists:mascotas,id',
            'servicio' => 'required|string',
            'veterinario' => 'nullable|string',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required|date_format:H:i',
            'motivo' => 'required|string',
            'notas' => 'nullable|string'
        ]);

        // Verificar que la mascota pertenece al usuario
        $mascota = Mascota::find($validated['mascota_id']);
        if ($mascota->user_id != $request->user()->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Obtener la especie de la mascota
        $especie = $mascota->especie;

        // Buscar el servicio por nombre
        $servicio = Servicio::where('nombre', 'like', '%' . $validated['servicio'] . '%')->first();
        $servicio_id = $servicio ? $servicio->id : null;

        // Buscar el veterinario por nombre si se proporciona
        $veterinario_id = null;
        if ($validated['veterinario']) {
            // Buscar por nombre exacto primero
            $veterinario = Veterinario::where('nombre', $validated['veterinario'])->first();
            // Si no encuentra, buscar por coincidencia parcial en ambos lados
            if (!$veterinario) {
                $veterinario = Veterinario::where('nombre', 'like', $validated['veterinario'] . '%')
                    ->orWhere('nombre', 'like', '%' . $validated['veterinario'])
                    ->first();
            }
            $veterinario_id = $veterinario ? $veterinario->id : null;
        }

        $cita = Cita::create([
            'user_id' => $request->user()->id,
            'mascota_id' => $validated['mascota_id'],
            'especie' => $especie,
            'servicio' => $validated['servicio'],
            'servicio_id' => $servicio_id,
            'veterinario' => $validated['veterinario'],
            'veterinario_id' => $veterinario_id,
            'fecha' => $validated['fecha'],
            'hora' => $validated['hora'],
            'motivo' => $validated['motivo'],
            'notas' => $validated['notas']
        ]);

        return response()->json([
            'message' => 'Cita agendada correctamente',
            'cita' => $cita->load(['mascota', 'veterinario', 'servicio'])
        ], 201);
    }

    // Actualizar cita
    public function update(Request $request, $id)
    {
        $cita = Cita::find($id);

        if (!$cita) {
            return response()->json(['message' => 'Cita no encontrada'], 404);
        }

        if ($cita->user_id != $request->user()->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validated = $request->validate([
            'fecha' => 'sometimes|date|after:today',
            'hora' => 'sometimes|date_format:H:i',
            'motivo' => 'sometimes|string',
            'notas' => 'nullable|string'
        ]);

        $cita->update($validated);

        return response()->json(['message' => 'Cita actualizada', 'data' => $cita]);
    }

    // Cancelar cita
    public function destroy(Request $request, $id)
    {
        $cita = Cita::find($id);

        if (!$cita) {
            return response()->json(['message' => 'Cita no encontrada'], 404);
        }

        if ($cita->user_id != $request->user()->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $cita->delete();

        return response()->json(['message' => 'Cita cancelada']);
    }

    // Obtener horarios disponibles para un veterinario en una fecha
    public function horariosDisponibles(Request $request)
    {
        $validated = $request->validate([
            'veterinario_id' => 'required|exists:veterinarios,id',
            'fecha' => 'required|date|after_or_equal:today'
        ]);

        $veterinario_id = $validated['veterinario_id'];
        $fecha = $validated['fecha'];

        // Horarios disponibles de la clínica (en horas)
        $horarios = ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30'];

        // Obtener citas ocupadas para ese veterinario en esa fecha
        // Convertir a formato HH:MM comparando solo horas y minutos
        $citasOcupadas = Cita::where('veterinario_id', $veterinario_id)
            ->where('fecha', $fecha)
            ->pluck('hora')
            ->map(function($hora) {
                // Tomar solo HH:MM del formato HH:MM:SS
                return substr($hora, 0, 5);
            })
            ->toArray();

        // Horarios disponibles (los que no están ocupados)
        $horariosDisponibles = array_diff($horarios, $citasOcupadas);

        return response()->json([
            'fecha' => $fecha,
            'veterinario_id' => $veterinario_id,
            'horarios_disponibles' => array_values($horariosDisponibles),
            'horarios_ocupados' => $citasOcupadas
        ]);
    }
}

