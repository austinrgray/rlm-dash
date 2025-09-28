<?php

use App\Enums\InvoiceStatus;
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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default(InvoiceStatus::Open->value);
            $table->date('due_date')->nullable();
            $table->timestamps();
        });

        DB::statement(
            'ALTER TABLE invoices
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
        Schema::dropIfExists('invoices');
    }
};
