<?php

use App\Enums\BurialRightStatus;
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
        Schema::create('burial_rights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('organization_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('plot_id')
                ->constrained()
                ->unique()
                ->cascadeOnDelete();
            $table->string('status')->default(BurialRightStatus::Active->value);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        DB::statement(
            'ALTER TABLE burial_rights
            ADD CONSTRAINT check_family_or_org
            CHECK (
                (family_id IS NOT NULL AND organization_id IS NULL)
                OR (family_id IS NULL AND organization_id IS NOT NULL)
            )'
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('burial_rights');
    }
};
