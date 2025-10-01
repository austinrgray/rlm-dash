<?php

use App\Enums\Finance\InvoiceStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internal_cemetery_id')->nullable()->constrained()->nullOnDelete();
            //$table->foreignId('internal_monument_retailer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('family_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default(InvoiceStatus::Open->value);
            $table->date('invoice_date')->default(DB::raw('CURRENT_DATE'));
            $table->date('due_date')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('balance_due', 10, 2)->default(0);

            // Integration hooks
            $table->string('external_reference')->nullable(); // for QuickBooks Desktop
            $table->json('metadata')->nullable();

            $table->timestamps();
        });

        // DB::statement("
        //     ALTER TABLE invoices
        //     ADD CONSTRAINT invoices_single_issuer
        //     CHECK (
        //         (internal_cemetery_id IS NOT NULL AND internal_monument_retailer_id IS NULL)
        //         OR (internal_cemetery_id IS NULL AND internal_monument_retailer_id IS NOT NULL)
        //     )
        // ");

        DB::statement("
            ALTER TABLE invoices
            ADD CONSTRAINT invoices_customer_required
            CHECK (
                (family_id IS NOT NULL) OR (organization_id IS NOT NULL)
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
