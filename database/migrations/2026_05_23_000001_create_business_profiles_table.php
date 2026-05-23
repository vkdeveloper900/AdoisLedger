<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedTinyInteger('business_type_id'); // 1=General 2=Dairy 3=Construction
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('manager_name')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('India');
            // Bank details
            $table->string('bank_name')->nullable();
            $table->string('bank_holder_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_ifsc_code')->nullable();
            $table->string('bank_branch')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_profiles');
    }
};
