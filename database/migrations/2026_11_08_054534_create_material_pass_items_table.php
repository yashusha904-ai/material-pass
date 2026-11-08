<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Перечень ТМЦ (inventory lines on a material pass).
     */
    public function up(): void
    {
        Schema::create('material_pass_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_pass_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('category')->nullable();
            $table->string('name');
            $table->foreignId('owner_external_organization_id')
                ->nullable()
                ->constrained('external_organizations')
                ->nullOnDelete();
            $table->string('owner_organization_name')->nullable();
            $table->decimal('quantity', 14, 3)->nullable();
            $table->string('packaging_type')->nullable();
            $table->decimal('total_weight', 14, 3)->nullable();
            $table->string('unit', 32)->nullable();
            $table->string('dump_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_pass_items');
    }
};
