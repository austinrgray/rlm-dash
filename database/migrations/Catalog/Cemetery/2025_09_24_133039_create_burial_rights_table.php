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
        Schema::create('burial_rights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('burial_right_bundle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plot_id')->constrained()->unique();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['burial_right_bundle_id', 'plot_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('burial_rights');
    }
};
