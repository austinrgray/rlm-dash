<?php

use App\Enums\Interment\DispositionType;
use App\Enums\Interment\IntermentStatus;
use App\Enums\Interment\VaultType;
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
        Schema::create('interments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('person_id')->constrained()->unique();
            $table->unsignedBigInteger('intermentable_id')->nullable();
            $table->string('intermentable_type')->nullable();
            $table->date('date_of_interment')->nullable();
            $table->string('disposition_type')->default(DispositionType::Unknown->value);
            $table->string('vault_type')->default(VaultType::Unknown->value);
            $table->foreignId('funeral_home_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('invoice_line_item_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default(IntermentStatus::Pending->value);

            $table->timestamps();
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
