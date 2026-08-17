<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('obras_sociales', function (Blueprint $table) {
            $table->text('condiciones')->nullable();
            $table->decimal('copago_adicional', 10, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('obras_sociales', function (Blueprint $table) {
            $table->dropColumn([
                'condiciones',
                'copago_adicional',
            ]);
        });
    }
};