<?php

namespace App\Http\Controllers;

use App\Models\HistorialMedico;
use App\Models\Mascota;
use Illuminate\Http\Request;

class HistorialMedicoController extends Controller
{
    // Obtener historial de una mascota
    public function index($mascotaId, Request $request)
    {
        $mascota = Mascota::find($mascotaId);

        if (!$mascota) {
            return response()->json(['message' => 'Mascota no encontrada'], 404);
        }

        if ($mascota->user_id != $request->user()->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $historial = HistorialMedico::where('mascota_id', $mascotaId)
            ->with(['veterinario', 'cita'])
            ->orderBy('fecha', 'desc')
            ->get();

        return response()->json($historial);
    }

    // Crear registro en historial (Solo admin)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mascota_id' => 'required|exists:mascotas,id',
            'veterinario_id' => 'nullable|exists:veterinarios,id',
            'cita_id' => 'nullable|exists:citas,id',
            'tipo' => 'required|in:consulta,vacunacion,cirugia,analisis,medicamento,internacion',
            'fecha' => 'required|date',
            'descripcion' => 'required|string',
            'resultado' => 'nullable|string',
            'medicamento' => 'nullable|string',
            'dosis' => 'nullable|string',
            'notas' => 'nullable|string'
        ]);

        $historial = HistorialMedico::create($validated);

        return response()->json(['message' => 'Registro creado', 'data' => $historial], 201);
    }
}
