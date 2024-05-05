<?php

use App\Enum\StatusConsulta;
use App\Models\Agenda;
use App\Models\Especialidade;
use App\Models\Horario;
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
            $table->date('dia');

            $table->enum('status', array_map(fn($item) => $item->name, StatusConsulta::cases()))->default(StatusConsulta::AGENDADO->name);
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Agenda::class)->constrained();
            $table->foreignIdFor(Especialidade::class)->constrained();
            $table->foreignIdFor(Horario::class)->constrained();
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
