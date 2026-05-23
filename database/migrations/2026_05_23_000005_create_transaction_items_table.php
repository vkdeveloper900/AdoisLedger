<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
            $table->string('description');
            $table->string('unit')->nullable();
            $table->decimal('qty', 10, 3)->default(0);
            $table->decimal('fat', 5, 2)->nullable();         // dairy only
            $table->decimal('rate', 10, 2)->default(0);
            $table->decimal('amount', 10, 2)->default(0);     // qty * rate
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
