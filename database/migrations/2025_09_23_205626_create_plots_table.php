<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lot_id')->constrained()->cascadeOnDelete();
            $table->string('plot_number');
            $table->string('grid_reference')->nullable();
            $table->integer('traditional_burials')->default(0);
            $table->integer('cremation_burials')->default(0);
            $table->integer('mausoleum_entombments')->default(0);
            $table->integer('columbarium_entombments')->default(0);
            $table->unsignedInteger('max_traditional_burials')->default(1);
            $table->unsignedInteger('max_cremation_burials')->default(1);
            $table->unsignedInteger('max_mausoleum_entombments')->default(0);
            $table->unsignedInteger('max_columbarium_entombments')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['lot_id', 'plot_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plots');
    }
};
