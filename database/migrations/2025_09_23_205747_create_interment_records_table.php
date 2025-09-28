<?php

use App\Enums\DispositionType;
use App\Enums\IntermentStatus;
use App\Enums\VaultType;
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
        Schema::create('interment_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained()->unique();
            $table->foreignId('external_cemetery_id')
                ->nullable()
                ->constrained('external_cemeteries')
                ->nullOnDelete();
            $table->foreignId('plot_id')->nullable()->constrained()->nullOnDelete();
            $table->date('date_of_death')->nullable();
            $table->date('date_of_interment')->nullable();
            $table->string('disposition_type')->default(DispositionType::Unknown->value);
            $table->string('vault_type')->default(VaultType::Unknown->value);
            $table->string('status')->default(IntermentStatus::Pending->value);
            $table->string('funeral_home')->nullable();
            $table->timestamps();
        });

        DB::statement(
            'ALTER TABLE interment_records
            ADD CONSTRAINT check_plot_or_external
            CHECK (
                (plot_id IS NOT NULL AND external_cemetery_id IS NULL)
                OR (plot_id IS NULL AND external_cemetery_id IS NOT NULL)
            )'
        );

        DB::statement(
            'ALTER TABLE interment_records
            ADD CONSTRAINT check_interment_after_death
            CHECK (
                date_of_interment IS NULL
                OR date_of_death IS NULL
                OR date_of_interment >= date_of_death
            )'
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interment_records');
    }
};
