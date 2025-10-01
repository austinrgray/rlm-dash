<?php

use App\Enums\Customer\Gender;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('date_of_birth')->nullable();
            $table->date('date_of_death')->nullable();
            $table->string('gender')->default(Gender::Unknown->value);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        DB::statement(
            'ALTER TABLE people
            ADD CONSTRAINT chk_dod_after_dob
            CHECK (
                date_of_birth IS NULL
                OR date_of_death IS NULL
                OR date_of_death >= date_of_birth
            )'
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
