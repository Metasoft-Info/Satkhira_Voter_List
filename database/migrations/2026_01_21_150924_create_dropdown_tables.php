<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Search Types
        Schema::create('search_types', function (Blueprint $table) {
            $table->id();
            $table->string('name_bn');
            $table->string('name_en');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Upazilas
        Schema::create('upazilas', function (Blueprint $table) {
            $table->id();
            $table->string('name_bn');
            $table->string('name_en');
            $table->timestamps();
        });

        // Unions
        Schema::create('unions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('upazila_id')->constrained()->onDelete('cascade');
            $table->string('name_bn');
            $table->string('name_en');
            $table->timestamps();
        });

        // Wards
        Schema::create('wards', function (Blueprint $table) {
            $table->id();
            $table->string('name_bn');
            $table->string('name_en');
            $table->timestamps();
        });

        // Vote Centers
        Schema::create('vote_centers', function (Blueprint $table) {
            $table->id();
            $table->string('center_no');
            $table->string('name_bn');
            $table->string('name_en');
            $table->timestamps();
        });

        // Area Codes
        Schema::create('area_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('upazila_id')->constrained()->onDelete('cascade');
            $table->foreignId('union_id')->constrained()->onDelete('cascade');
            $table->foreignId('ward_id')->constrained()->onDelete('cascade');
            $table->foreignId('vote_center_id')->constrained()->onDelete('cascade');
            $table->string('area_code_no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('area_codes');
        Schema::dropIfExists('vote_centers');
        Schema::dropIfExists('wards');
        Schema::dropIfExists('unions');
        Schema::dropIfExists('upazilas');
        Schema::dropIfExists('search_types');
    }
};
