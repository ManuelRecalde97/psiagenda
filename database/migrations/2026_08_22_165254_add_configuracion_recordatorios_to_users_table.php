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
        Schema::table('users', function (Blueprint $table) {
        $table->boolean('enviar_recordatorios')->default(true);
        $table->string('frecuencia_recordatorio')->default('24hs'); // '8hs', '12hs', '24hs', 'ambos'
        $table->time('hora_envio_diario')->default('09:00:00'); // Hora del día para disparar el recordatorio
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
