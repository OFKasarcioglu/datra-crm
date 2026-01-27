<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vehicle_maintenances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('vehicle_id')
                ->constrained('vehicles')
                ->cascadeOnDelete();

            $table->foreignId('maintenance_type_id')
                ->constrained('maintenance_types');

            $table->date('maintenance_date');           // Yapıldığı tarih
            $table->unsignedInteger('km')->nullable();  // Yapıldığı km

            $table->date('next_maintenance_date')->nullable();
            $table->unsignedInteger('next_km')->nullable();

            $table->decimal('cost', 10, 2)->default(0); // Tutar
            $table->string('service_name')->nullable(); // Servis / firma
            $table->text('description')->nullable();

            $table->enum('status', [
                'planned',
                'completed',
                'cancelled'
            ])->default('planned');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_maintenances');
    }
};