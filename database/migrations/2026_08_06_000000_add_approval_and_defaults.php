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
        Schema::table('quotations', function (Blueprint $table) {
            $table->boolean('is_approved')->default(false)->after('notes');
        });

        Schema::table('company_settings', function (Blueprint $table) {
            $table->decimal('organizing_fee_percent', 5, 2)->default(10.00)->after('email');
            $table->decimal('ppn_percent', 5, 2)->default(11.00)->after('organizing_fee_percent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn('is_approved');
        });

        Schema::table('company_settings', function (Blueprint $table) {
            $table->dropColumn(['organizing_fee_percent', 'ppn_percent']);
        });
    }
};
