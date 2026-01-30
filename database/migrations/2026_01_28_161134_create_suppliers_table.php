<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();

            /* =======================
               TEMEL
            ======================= */
            $table->string('supplier_code', 30)->unique();
            $table->string('name');              // Firma adı
            $table->string('short_title')->nullable();
            $table->string('title')->nullable(); // Resmi unvan

            /* =======================
               VERGİ
            ======================= */
            $table->string('tax_number')->nullable()->unique();
            $table->string('tax_office')->nullable();

            /* =======================
               ADRES
            ======================= */
            $table->text('address')->nullable();
            $table->string('country')->default('Türkiye');
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('postal_code')->nullable();

            /* =======================
               İLETİŞİM
            ======================= */
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable()->unique();

            /* =======================
               YETKİLİLER
            ======================= */
            $table->string('contact1_name')->nullable();
            $table->string('contact1_phone')->nullable();
            $table->string('contact1_email')->nullable();

            $table->string('contact2_name')->nullable();
            $table->string('contact2_phone')->nullable();
            $table->string('contact2_email')->nullable();

            /* =======================
               FİNANS
            ======================= */
            $table->string('currency', 3)->default('TRY');
            $table->decimal('payment_term', 5, 0)->nullable(); // vade (gün)
            $table->decimal('credit_limit', 15, 2)->nullable();

            /* =======================
               DURUM
            ======================= */
            $table->boolean('is_active')->default(true);

            /* =======================
               SİSTEM
            ======================= */
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
