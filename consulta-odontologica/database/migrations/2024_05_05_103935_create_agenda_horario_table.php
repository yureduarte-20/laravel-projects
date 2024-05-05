<?php

use App\Models\Agenda;
use App\Models\Horario;
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
        Schema::create('agenda_horario', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Agenda::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Horario::class)->constrained();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_horario');
    }
};
