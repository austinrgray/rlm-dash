<?php

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
        Schema::create('lots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained()->cascadeOnDelete();
            $table->string('lot_number')->nullable();
            $table->string('lot_letter')->nullable();
            $table->integer('max_capacity')->default(6);
            $table->integer('available_plot_count')->default(6);
            $table->string('grid_reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['section_id', 'lot_number', 'lot_letter']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lots');
    }
};
