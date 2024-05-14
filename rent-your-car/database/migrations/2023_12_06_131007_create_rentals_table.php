<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Car::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\User::class)->constrained()->cascadeOnDelete();
            $table->timestamp('rental_date')->nullable();
            $table->dateTime('expected_rental');
            $table->dateTime('expected_return');
            $table->timestamp('returned_at')->nullable();
            $table->decimal('total_cost')->nullable();
            $table->decimal('estimated_cost');
            $table->enum('status', [\App\RentalStatus::IN_PROGRESS->value, \App\RentalStatus::CANCELED->value, \App\RentalStatus::LATE->value, \App\RentalStatus::FINISHED->value, \App\RentalStatus::RESERVED->value]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
