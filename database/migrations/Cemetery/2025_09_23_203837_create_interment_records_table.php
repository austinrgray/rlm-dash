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
        Schema::create('interment_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained()->unique();
            $table->date('date_of_death')->nullable();
            $table->date('date_of_interment')->nullable();
            $table->foreignId('internal_cemetery_id')->nullable()->constrained('internal_cemeteries');
            $table->foreignId('external_cemetery_id')->nullable()->constrained('external_cemeteries');
            $table->foreignId('plot_id')->nullable()->constrained();
            $table->enum('disposition_type', [
                'traditional_burial',
                'cremation_burial',
                'columbarium',
                'mausoleum',
                'other',
                'unknown'
            ])->default('unknown');
            $table->enum('vault_type', [
                'concrete',
                'bronze',
                'steel',
                'urn_cremation',
                'none_cremation',
                'other',
                'unknown'
            ])->default('unknown');
            $table->string('funeral_home')->nullable();
            $table->foreignId('order_id')->nullable()->constrained();
            $table->timestamps();

            $table->checkConstraint('(
                (internal_cemetery_id IS NOT NULL AND external_cemetery_id IS NULL) OR
                (internal_cemetery_id IS NULL AND external_cemetery_id IS NOT NULL)
            )');
            $table->checkConstraint('(plot_id IS NULL OR internal_cemetery_id IS NOT NULL)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interment_records');
    }
};
