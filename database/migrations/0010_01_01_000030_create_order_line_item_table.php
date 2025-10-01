<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_line_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_line_item_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['order_id', 'invoice_line_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_line_item');
    }
};
