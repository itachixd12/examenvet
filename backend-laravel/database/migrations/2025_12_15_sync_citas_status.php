<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Actualizar todas las citas sin status
        DB::table('citas')
            ->whereNull('status')
            ->update(['status' => 'Próxima']);
    }

    public function down(): void
    {
        //
    }
};
