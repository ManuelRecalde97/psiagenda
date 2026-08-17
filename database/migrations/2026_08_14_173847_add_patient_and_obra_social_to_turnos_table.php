<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('turnos', function (Blueprint $table) {

            // Agregar patient_id solamente si todavía no existe
            if (!Schema::hasColumn('turnos', 'patient_id')) {
                $table->foreignId('patient_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('patients')
                    ->nullOnDelete();
            }

            // Agregar obra_social_id solamente si todavía no existe
            if (!Schema::hasColumn('turnos', 'obra_social_id')) {
                $table->foreignId('obra_social_id')
                    ->nullable()
                    ->after('patient_id')
                    ->constrained('obras_sociales')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('turnos', function (Blueprint $table) {

            if (Schema::hasColumn('turnos', 'patient_id')) {
                $table->dropForeign(['patient_id']);
                $table->dropColumn('patient_id');
            }

            if (Schema::hasColumn('turnos', 'obra_social_id')) {
                $table->dropForeign(['obra_social_id']);
                $table->dropColumn('obra_social_id');
            }
        });
    }
};