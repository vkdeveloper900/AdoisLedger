<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('party_type')->default('customer'); // customer | vendor | both
            $table->string('mobile')->nullable();
            $table->string('mobile2')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('profile_image')->nullable();
            // Bank & payment details
            $table->string('bank_account_number')->nullable();
            $table->string('bank_ifsc_code')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('upi_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
