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
        Schema::create('voters', function (Blueprint $table) {
            $table->id();
            $table->string('serial_no', 50)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('voter_id', 50)->nullable();
            $table->string('father_name', 255)->nullable();
            $table->string('mother_name', 255)->nullable();
            $table->string('profession', 100)->nullable();
            $table->string('dob', 50)->nullable();
            $table->text('address')->nullable();
            $table->string('union_ward', 100)->nullable();
            $table->string('upazila', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voters');
    }
};
