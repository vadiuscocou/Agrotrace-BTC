<?php

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
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // investor
            $table->decimal('amount_fcfa', 15, 2);
            $table->decimal('amount_sats', 20, 0);
            $table->decimal('fee_sats', 20, 0)->default(0); // 2% fee
            $table->string('payment_request')->nullable(); // Lightning invoice
            $table->string('payment_hash')->nullable();
            $table->string('status')->default('pending'); // pending, paid
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
