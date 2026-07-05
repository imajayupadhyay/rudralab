<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table): void {
            $table->id();
            $table->string('certificate_number', 80)->unique();
            $table->string('normalized_certificate_number', 80)->unique();
            $table->date('issued_at')->nullable();
            $table->string('customer_name', 160)->nullable();
            $table->string('weight', 80)->nullable();
            $table->string('shape_cut', 120)->nullable();
            $table->string('dimension', 120)->nullable();
            $table->string('colour', 120)->nullable();
            $table->string('refractive_index', 120)->nullable();
            $table->string('specific_gravity', 120)->nullable();
            $table->string('origin', 160)->nullable();
            $table->text('remarks')->nullable();
            $table->string('image_path')->nullable();
            $table->json('extra_fields')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
