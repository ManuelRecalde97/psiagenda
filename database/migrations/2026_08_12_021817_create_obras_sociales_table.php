<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('obras_sociales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nombre');
            $table->text('condiciones')->nullable(); //guardamos los textos del copago, tokens, etc
            $table->decimal('copago_adicional' , 10, 2)->nullable(); //por si quiere poner un monto fijo exxtra
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('obras_sociales');
    }
};