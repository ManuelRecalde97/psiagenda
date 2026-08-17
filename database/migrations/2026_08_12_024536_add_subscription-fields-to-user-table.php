<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('suscripcion_estado')->default('pendiente'); // activo, pendiente, cancelado
            $table->timestamp('suscripcion_vencimiento')->nullable();
            $table->string('mp_subscription_id')->nullable(); // ID de la suscripción de Mercado Pago
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['suscripcion_estado', 'suscripcion_vencimiento', 'mp_subscription_id']);
        });
    }
};