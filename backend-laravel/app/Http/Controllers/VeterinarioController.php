<?php

namespace App\Http\Controllers;

use App\Models\Veterinario;
use Illuminate\Http\Request;

class VeterinarioController extends Controller
{
    // Obtener todos los veterinarios
    public function index()
    {
        $veterinarios = Veterinario::all();
        return response()->json($veterinarios);
    }

    // Obtener un veterinario específico
    public function show($id)
    {
        $veterinario = Veterinario::with('citas', 'horarios')->find($id);

        if (!$veterinario) {
            return response()->json(['message' => 'Veterinario no encontrado'], 404);
        }

        return response()->json($veterinario);
    }

    // Crear veterinario (Solo admin)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:veterinarios,email',
            'telefono' => 'nullable|string|max:20',
            'especialidad' => 'nullable|string|max:255',
            'foto' => 'nullable|url'
        ]);

        $veterinario = Veterinario::create($validated);

        return response()->json(['message' => 'Veterinario creado', 'data' => $veterinario], 201);
    }

    // Actualizar veterinario (Solo admin)
    public function update(Request $request, $id)
    {
        $veterinario = Veterinario::find($id);

        if (!$veterinario) {
            return response()->json(['message' => 'Veterinario no encontrado'], 404);
        }

        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:veterinarios,email,' . $id,
            'telefono' => 'nullable|string|max:20',
            'especialidad' => 'nullable|string|max:255',
            'foto' => 'nullable|url'
        ]);

        $veterinario->update($validated);

        return response()->json(['message' => 'Veterinario actualizado', 'data' => $veterinario]);
    }

    // Eliminar veterinario (Solo admin)
    public function destroy($id)
    {
        $veterinario = Veterinario::find($id);

        if (!$veterinario) {
            return response()->json(['message' => 'Veterinario no encontrado'], 404);
        }

        $veterinario->delete();

        return response()->json(['message' => 'Veterinario eliminado']);
    }
}
