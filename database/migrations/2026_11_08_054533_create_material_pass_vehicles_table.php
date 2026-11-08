<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Данные ТС (vehicles on a material pass).
     */
    public function up(): void
    {
        Schema::create('material_pass_vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_pass_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('booking_request')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->string('plate_number')->nullable();
            $table->foreignId('driver_person_id')->nullable()->constrained('people')->nullOnDelete();
            $table->string('driver_name')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_pass_vehicles');
    }
};
