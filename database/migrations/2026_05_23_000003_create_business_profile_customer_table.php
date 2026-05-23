<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_profile_customer', function (Blueprint $table) {
            $table->foreignId('business_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->primary(['business_profile_id', 'customer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_profile_customer');
    }
};
