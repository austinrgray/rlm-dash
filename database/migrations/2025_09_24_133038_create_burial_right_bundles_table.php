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
        Schema::create('burial_right_bundles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_id')->nullable()->constrained();
            $table->foreignId('organization_id')->nullable()->constrained();
            $table->foreignId('order_id')->nullable()->constrained();
            $table->date('transaction_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        DB::statement(
            'ALTER TABLE burial_right_bundles
            ADD CONSTRAINT burial_right_bundles_family_or_org_check
            CHECK ((family_id IS NOT NULL AND organization_id IS NULL)
            OR (family_id IS NULL AND organization_id IS NOT NULL))'
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('burial_right_bundles');
    }
};
