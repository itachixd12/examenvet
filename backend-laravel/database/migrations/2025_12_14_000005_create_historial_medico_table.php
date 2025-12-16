<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('historial_medico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mascota_id')->constrained()->onDelete('cascade');
            $table->foreignId('veterinario_id')->nullable()->constrained('veterinarios')->onDelete('set null');
            $table->foreignId('cita_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('tipo', ['consulta', 'vacunacion', 'cirugia', 'analisis', 'medicamento', 'internacion']);
            $table->date('fecha');
            $table->text('descripcion');
            $table->text('resultado')->nullable();
            $table->string('medicamento')->nullable();
            $table->string('dosis')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_medico');
    }
};
