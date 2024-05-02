<?php

use App\Models\Disponibilidade;
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
        Schema::create('disponibilidade_horarios', function (Blueprint $table) {
            $table->id();
            $table->time('horario_inicio');
            $table->time('horario_final');
            $table->foreignIdFor(Disponibilidade::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disponibilidade_horarios');
    }
};
