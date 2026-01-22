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
        Schema::table('voters', function (Blueprint $table) {
            $table->string('name_en', 255)->nullable()->after('name');
            $table->string('father_name_en', 255)->nullable()->after('father_name');
            $table->string('mother_name_en', 255)->nullable()->after('mother_name');
            $table->string('profession_en', 100)->nullable()->after('profession');
            $table->text('address_en')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voters', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'father_name_en', 'mother_name_en', 'profession_en', 'address_en']);
        });
    }
};
