<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_quote_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sales_quote_id')
                ->constrained()
                ->cascadeOnDelete();

            /* =======================
               KALEM
            ======================= */
            $table->string('description');
            $table->string('drawing_number')->nullable();
            $table->string('payment_term')->nullable();
            $table->date('delivery_date')->nullable();

            /* =======================
               HESAPLAR
            ======================= */
            $table->unsignedInteger('quantity');           // PCS
            $table->decimal('unit_weight', 10, 2);          // KG
            $table->decimal('total_weight', 10, 2);         // auto
            $table->decimal('unit_price', 10, 2);           // €/kg
            $table->decimal('total_price', 15, 2);          // auto

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_quote_items');
    }
};
