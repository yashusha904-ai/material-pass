<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Согласование (approval stages / history).
     */
    public function up(): void
    {
        Schema::create('material_pass_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_pass_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedInteger('version')->default(1);
            $table->string('stage_name');
            $table->json('participants')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('arrived_at')->nullable();
            $table->string('result')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolver_employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('resolver_name_title')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_pass_approvals');
    }
};
