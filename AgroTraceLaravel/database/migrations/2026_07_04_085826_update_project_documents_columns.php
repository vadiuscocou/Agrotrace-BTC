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
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'supporting_documents')) {
                $table->dropColumn('supporting_documents');
            }
            $table->string('registration_certificate')->nullable();
            $table->string('signatories_id')->nullable();
            $table->string('bank_account_proof')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['registration_certificate', 'signatories_id', 'bank_account_proof']);
            $table->string('supporting_documents')->nullable();
        });
    }
};
