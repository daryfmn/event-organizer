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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events');
            $table->foreignId('signed_by')->nullable()->constrained('users');
            $table->string('quotation_number', 100);
            $table->string('variant_label', 100)->nullable();
            $table->date('variant_start_date')->nullable();
            $table->date('variant_end_date')->nullable();
            $table->decimal('organizing_fee_percent', 5, 2)->default(10.00);
            $table->decimal('ppn_percent', 5, 2)->default(11.00);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('organizing_fee', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('ppn', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
