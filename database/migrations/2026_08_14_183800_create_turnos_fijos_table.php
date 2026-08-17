<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('turnos_fijos', function (Blueprint $table) {
            $table->id();

            // Psicólogo dueño del turno fijo
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Paciente que tiene el turno fijo
            $table->foreignId('patient_id')
                ->constrained('patients')
                ->onDelete('cascade');

            // Obra social utilizada habitualmente
            $table->foreignId('obra_social_id')
                ->nullable()
                ->constrained('obras_sociales')
                ->nullOnDelete();

             //Turnos_fijos
            $table->foreignId('turno_id')
                ->nullable()
                ->constrained('turnos')
                ->nullOnDelete();

            // 0 = domingo, 1 = lunes, ... 6 = sábado
            $table->unsignedTinyInteger('dia_semana');

            // Ejemplo: 16:00
            $table->time('hora');

            // Actualmente vamos a trabajar con semanal
            $table->string('frecuencia')->default('semanal');

            // Desde cuándo comienza el turno fijo
            $table->date('fecha_inicio');

            // Puede quedar sin fecha de finalización
            $table->date('fecha_fin')->nullable();

            // Permite desactivar el turno fijo sin eliminarlo
            $table->boolean('activo')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('turnos_fijos');
    }
};
