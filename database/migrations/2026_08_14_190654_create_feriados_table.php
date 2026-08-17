<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feriados', function (Blueprint $table) {
            $table->id();

            // Psicólogo dueño del feriado
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Fecha del feriado
            $table->date('fecha');

            // Ejemplo: Feriado nacional, vacaciones, día sin atención, etc.
            $table->string('motivo')->nullable();

            $table->timestamps();

            // Un psicólogo no puede tener dos veces el mismo feriado
            $table->unique(['user_id', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feriados');
    }
};