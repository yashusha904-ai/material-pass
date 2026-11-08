<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Материальный пропуск (material pass) — header + initiator / issued-to snapshots.
     */
    public function up(): void
    {
        Schema::create('material_passes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('number');
            $table->date('document_date');
            $table->string('status')->default('draft');
            $table->string('qr_token')->nullable()->unique();

            // Данные инициатора
            $table->foreignId('initiator_employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('initiator_department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('initiator_organization_name')->nullable();

            // Материальный пропуск (core fields)
            $table->foreignId('from_department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('to_department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('to_location_name')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->json('seal_numbers')->nullable();

            // Выдан сотруднику
            $table->foreignId('issued_to_employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('issued_to_department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('issued_to_organization_name')->nullable();
            $table->foreignId('issued_to_external_organization_id')
                ->nullable()
                ->constrained('external_organizations')
                ->nullOnDelete();

            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['number', 'document_date']);
            $table->index('status');
            $table->index('valid_until');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_passes');
    }
};
