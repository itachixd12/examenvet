<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    // Obtener todos los servicios
    public function index()
    {
        $servicios = Servicio::all();
        return response()->json($servicios);
    }

    // Obtener un servicio específico
    public function show($id)
    {
        $servicio = Servicio::find($id);

        if (!$servicio) {
            return response()->json(['message' => 'Servicio no encontrado'], 404);
        }

        return response()->json($servicio);
    }

    // Crear servicio (Solo admin)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'duracion' => 'required|integer|min:15'
        ]);

        $servicio = Servicio::create($validated);

        return response()->json(['message' => 'Servicio creado', 'data' => $servicio], 201);
    }

    // Actualizar servicio (Solo admin)
    public function update(Request $request, $id)
    {
        $servicio = Servicio::find($id);

        if (!$servicio) {
            return response()->json(['message' => 'Servicio no encontrado'], 404);
        }

        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'descripcion' => 'sometimes|string',
            'precio' => 'sometimes|numeric|min:0',
            'duracion' => 'sometimes|integer|min:15'
        ]);

        $servicio->update($validated);

        return response()->json(['message' => 'Servicio actualizado', 'data' => $servicio]);
    }

    // Eliminar servicio (Solo admin)
    public function destroy($id)
    {
        $servicio = Servicio::find($id);

        if (!$servicio) {
            return response()->json(['message' => 'Servicio no encontrado'], 404);
        }

        $servicio->delete();

        return response()->json(['message' => 'Servicio eliminado']);
    }
}
