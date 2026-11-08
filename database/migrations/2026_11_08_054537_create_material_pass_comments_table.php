<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Комментарии (comments on a material pass).
     */
    public function up(): void
    {
        Schema::create('material_pass_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_pass_id')->constrained()->cascadeOnDelete();
            $table->timestamp('commented_at');
            $table->string('role')->nullable();
            $table->text('body');
            $table->foreignId('author_person_id')->nullable()->constrained('people')->nullOnDelete();
            $table->foreignId('author_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_pass_comments');
    }
};
