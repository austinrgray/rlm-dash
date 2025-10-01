<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('columbaria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internal_cemetery_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('grid_reference');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['internal_cemetery_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('columbaria');
    }
};
