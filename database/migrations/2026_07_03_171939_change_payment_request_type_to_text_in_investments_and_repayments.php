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
        Schema::table('investments', function (Blueprint $table) {
            $table->text('payment_request')->nullable()->change();
        });

        Schema::table('repayments', function (Blueprint $table) {
            $table->text('payment_request')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('investments_and_repayments', function (Blueprint $table) {
            //
        });
    }
};
