<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();

            /* =======================
               FİRMA BİLGİLERİ
            ======================= */
            $table->string('company_name');
            $table->string('address')->nullable();
            $table->string('tax_office')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();

            /* =======================
               PROFORMA / TEKLİF
            ======================= */
            $table->string('default_currency', 3)->default('EUR');
            $table->unsignedTinyInteger('default_vat_rate')->default(20);

            $table->string('quote_prefix')->default('DS');
            $table->unsignedSmallInteger('quote_start_number')->default(1000);
            $table->string('quote_year_format')->default('YY'); 
            // YY => 26 , YYYY => 2026

            /* =======================
               VARSAYILAN NOTLAR
            ======================= */
            $table->text('note_1')->nullable();
            $table->text('note_2')->nullable();
            $table->text('note_3')->nullable();
            $table->text('note_4')->nullable();
            $table->text('note_5')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
