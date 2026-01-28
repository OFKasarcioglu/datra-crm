<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            /* =======================
               KİMLİK BİLGİLERİ
            ======================= */
            $table->string('sicil_no', 20)->unique();
            $table->string('first_name');
            $table->string('last_name');

            $table->string('tc_no', 11)->nullable()->unique();
            $table->date('birth_date')->nullable();

            $table->enum('gender', ['male', 'female'])->nullable();
            $table->enum('marital_status', ['single', 'married'])->nullable();

            $table->string('photo')->nullable();

            /* =======================
               İŞ BİLGİLERİ
            ======================= */
            $table->foreignId('department_id')
                ->constrained('departments')
                ->restrictOnDelete();

            $table->foreignId('position_id')
                ->constrained('positions')
                ->restrictOnDelete();

            $table->date('hire_date');
            $table->date('exit_date')->nullable();

            $table->boolean('status')->default(true);

            $table->enum('employment_type', [
                'full',       // Tam zamanlı
                'part',       // Yarı zamanlı
                'outsourced', // Taşeron
            ])->default('full');

            $table->enum('employee_type', [
                'blue',    // Mavi yaka
                'white',   // Beyaz yaka
                'manager', // Yönetici
            ])->default('blue');

            /* =======================
               İLETİŞİM
            ======================= */
            $table->string('phone')->nullable();
            $table->string('email')->nullable()->unique();

            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();

            $table->string('emergency_contact')->nullable();
            $table->string('emergency_phone')->nullable();

            /* =======================
               SGK / SAĞLIK
            ======================= */
            $table->string('sgk_no')->nullable();
            $table->date('sgk_start')->nullable();
            $table->date('sgk_end')->nullable();

            $table->string('blood_type', 5)->nullable();
            $table->unsignedTinyInteger('disability_rate')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
