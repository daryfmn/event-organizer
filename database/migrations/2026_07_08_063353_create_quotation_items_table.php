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
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained('quotations');
            $table->foreignId('vendor_id')->nullable()->constrained('vendors');
            $table->enum('section', [
                'pre_event',
                'secretariat',
                'team_management',
                'venue',
                'production'
            ]);
            $table->string('sub_section', 100)->nullable();
            $table->string('description', 200);
            $table->string('item_detail', 200)->nullable();
            $table->decimal('qty', 10, 2)->default(1);
            $table->string('unit', 50)->nullable();
            $table->decimal('length', 10, 2)->default(1);
            $table->string('length_unit', 20)->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
    }
};
