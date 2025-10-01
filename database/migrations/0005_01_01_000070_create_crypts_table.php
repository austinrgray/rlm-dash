<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('crypts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internal_cemetery_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mausoleum_id')->constrained()->cascadeOnDelete();
            $table->string('mausoleum_name');
            $table->unsignedInteger('crypt_number');
            $table->string('grid_reference')->nullable();
            $table->unsignedInteger('capacity')->default(2);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['mausoleum_id', 'crypt_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crypts');
    }
};
