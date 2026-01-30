<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_quotes', function (Blueprint $table) {
            $table->id();

            /* =======================
               META
            ======================= */
            $table->string('quote_no')->unique();          // DS25-1143-01
            $table->date('quote_date');

            /* =======================
               MÜŞTERİ
            ======================= */
            $table->foreignId('customer_id')
                ->constrained('plan_customers')
                ->cascadeOnDelete();

            $table->string('customer_name');   // PDF için snapshot
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();

            /* =======================
               FİNANS
            ======================= */
            $table->string('currency', 3);
            $table->unsignedTinyInteger('vat_rate');

            $table->decimal('sub_total', 15, 2)->default(0);
            $table->decimal('vat_amount', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);

            /* =======================
               DURUM
            ======================= */
            $table->enum('status', [
                'draft',      // taslak
                'sent',       // gönderildi
                'approved',   // onaylandı
                'rejected',   // reddedildi
            ])->default('draft');

            /* =======================
               NOTLAR
            ======================= */
            $table->text('note_1')->nullable();
            $table->text('note_2')->nullable();
            $table->text('note_3')->nullable();
            $table->text('note_4')->nullable();
            $table->text('note_5')->nullable();

            /* =======================
               SİSTEM
            ======================= */
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_quotes');
    }
};
