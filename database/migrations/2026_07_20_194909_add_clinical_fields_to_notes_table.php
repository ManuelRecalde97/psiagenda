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
        Schema::table('notes', function (Blueprint $table) {
            $table->string('diagnostico')->nullable();
        $table->text('tareas_indicaciones')->nullable();
        $table->decimal('monto', 8, 2)->nullable();
        $table->string('estado_pago')->default('pendiente'); // Pendiente, Pagado
        $table->string('tipo_pago')->nullable(); // Obra Social, Particular
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            //
        });
    }
};
