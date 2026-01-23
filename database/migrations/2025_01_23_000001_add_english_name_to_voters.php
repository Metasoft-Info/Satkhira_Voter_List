<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('voters', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
            $table->string('father_name_en')->nullable()->after('father_name');
            $table->string('mother_name_en')->nullable()->after('mother_name');
            
            // Index for English search
            $table->index('name_en');
            $table->index('father_name_en');
            $table->index('mother_name_en');
        });
    }

    public function down(): void
    {
        Schema::table('voters', function (Blueprint $table) {
            $table->dropIndex(['name_en']);
            $table->dropIndex(['father_name_en']);
            $table->dropIndex(['mother_name_en']);
            $table->dropColumn(['name_en', 'father_name_en', 'mother_name_en']);
        });
    }
};
