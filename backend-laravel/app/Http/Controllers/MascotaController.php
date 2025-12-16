<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use Illuminate\Http\Request;

class MascotaController extends Controller
{
    // Obtener todas las mascotas del usuario autenticado
    public function index(Request $request)
    {
        $mascotas = Mascota::where('user_id', $request->user()->id)->get();
        return response()->json($mascotas);
    }

    // Obtener una mascota específica
    public function show($id)
    {
        $mascota = Mascota::find($id);
        
        if (!$mascota) {
            return response()->json(['message' => 'Mascota no encontrada'], 404);
        }

        return response()->json($mascota);
    }

    // Crear nueva mascota
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'especie' => 'required|string|max:100',
            'raza' => 'required|string|max:100',
            'edad' => 'required|numeric|min:0',
            'peso' => 'nullable|numeric|min:0',
            'foto' => 'nullable|url',
            'notas' => 'nullable|string'
        ]);

        $mascota = Mascota::create([
            'user_id' => $request->user()->id,
            'nombre' => $validated['nombre'],
            'especie' => $validated['especie'],
            'raza' => $validated['raza'],
            'edad' => $validated['edad'],
            'peso' => $validated['peso'] ?? null,
            'foto' => $validated['foto'] ?? null,
            'notas' => $validated['notas'] ?? null
        ]);

        return response()->json(['message' => 'Mascota creada exitosamente', 'data' => $mascota], 201);
    }

    // Actualizar mascota
    public function update(Request $request, $id)
    {
        $mascota = Mascota::find($id);

        if (!$mascota) {
            return response()->json(['message' => 'Mascota no encontrada'], 404);
        }

        // Verificar que el usuario sea propietario
        if ($mascota->user_id != $request->user()->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'especie' => 'sometimes|string|max:100',
            'raza' => 'sometimes|string|max:100',
            'edad' => 'sometimes|numeric|min:0',
            'peso' => 'nullable|numeric|min:0',
            'foto' => 'nullable|url',
            'notas' => 'nullable|string'
        ]);

        $mascota->update($validated);

        return response()->json(['message' => 'Mascota actualizada', 'data' => $mascota]);
    }

    // Eliminar mascota
    public function destroy(Request $request, $id)
    {
        $mascota = Mascota::find($id);

        if (!$mascota) {
            return response()->json(['message' => 'Mascota no encontrada'], 404);
        }

        if ($mascota->user_id != $request->user()->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $mascota->delete();

        return response()->json(['message' => 'Mascota eliminada exitosamente']);
    }
}
