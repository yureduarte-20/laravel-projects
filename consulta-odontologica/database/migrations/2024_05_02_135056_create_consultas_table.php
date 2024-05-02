<?php

use App\Models\Agenda;
use App\Models\DisponibilidadeHorario;
use App\Models\Especialidade;
use App\Models\User;
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
        Schema::create('consultas', function (Blueprint $table) {
            $table->id();

            $table->date('horario');


            $table->foreignIdFor(Especialidade::class);

            $table->foreignIdFor(DisponibilidadeHorario::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Agenda::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultas');
    }
};
