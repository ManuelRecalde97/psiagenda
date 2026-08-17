<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('mensaje_bienvenida')->nullable();
            $table->boolean('activar_edad')->default(true);
            $table->boolean('activar_modalidad')->default(true);
            $table->boolean('activar_motivo')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'mensaje_bienvenida',
                'activar_edad',
                'activar_modalidad',
                'activar_motivo'
            ]);
        });
    }
};
