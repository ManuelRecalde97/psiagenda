<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('obras_sociales', function (Blueprint $table) {
            if (!Schema::hasColumn('obras_sociales', 'condiciones')) {
                $table->text('condiciones')->nullable();
            }
            if (!Schema::hasColumn('obras_sociales', 'copago_adicional')) {
                $table->decimal('copago_adicional', 10, 2)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('obras_sociales', function (Blueprint $table) {
            $columnsToDrop = [];
            
            if (Schema::hasColumn('obras_sociales', 'condiciones')) {
                $columnsToDrop[] = 'condiciones';
            }
            if (Schema::hasColumn('obras_sociales', 'copago_adicional')) {
                $columnsToDrop[] = 'copago_adicional';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};