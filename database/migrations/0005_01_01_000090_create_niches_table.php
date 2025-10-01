<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('niches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internal_cemetery_id')->constrained()->cascadeOnDelete();
            $table->foreignId('columbarium_id')->constrained()->cascadeOnDelete();
            $table->string('columbarium_name');
            $table->unsignedInteger('niche_number');
            $table->string('grid_reference');
            $table->unsignedInteger('capacity')->default(3);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['columbarium_id', 'niche_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('niches');
    }
};
