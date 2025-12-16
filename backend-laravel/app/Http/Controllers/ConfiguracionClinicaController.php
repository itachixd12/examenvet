<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionClinica;
use Illuminate\Http\Request;

class ConfiguracionClinicaController extends Controller
{
    // Obtener configuración de la clínica
    public function show()
    {
        $config = ConfiguracionClinica::first();

        if (!$config) {
            return response()->json(['message' => 'Configuración no encontrada'], 404);
        }

        return response()->json($config);
    }

    // Actualizar configuración (Solo admin)
    public function update(Request $request)
    {
        $config = ConfiguracionClinica::first();

        if (!$config) {
            $config = new ConfiguracionClinica();
        }

        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'direccion' => 'sometimes|string',
            'telefono' => 'sometimes|string|max:20',
            'email' => 'sometimes|email',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'horario_apertura' => 'sometimes|date_format:H:i',
            'horario_cierre' => 'sometimes|date_format:H:i',
            'telefono_emergencia' => 'sometimes|string|max:20',
            'mapbox_token' => 'nullable|string'
        ]);

        $config->fill($validated)->save();

        return response()->json(['message' => 'Configuración actualizada', 'data' => $config]);
    }
}
