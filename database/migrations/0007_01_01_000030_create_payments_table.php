<?php

use App\Enums\Finance\PaymentMethodType;
use App\Enums\Finance\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->default(PaymentMethodType::Unknown->value);
            $table->string('status')->default(PaymentStatus::Pending->value);

            // References
            $table->string('transaction_ref')->nullable(); // processor or QB ref
            $table->json('metadata')->nullable();          // freeform processor/QB metadata
            $table->date('date');
            $table->timestamps();
        });

        Schema::create('invoice_payment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount_applied', 10, 2);
            $table->timestamps();
            $table->unique(['payment_id', 'invoice_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_payment');
        Schema::dropIfExists('payments');
    }
};
