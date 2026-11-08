<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Маршрут (checkpoint passage log).
     */
    public function up(): void
    {
        Schema::create('material_pass_route_passages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_pass_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('checkpoint');
            $table->string('passage_status')->nullable();
            $table->timestamp('passed_at')->nullable();
            $table->foreignId('security_officer_person_id')->nullable()->constrained('people')->nullOnDelete();
            $table->string('security_officer_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_pass_route_passages');
    }
};
