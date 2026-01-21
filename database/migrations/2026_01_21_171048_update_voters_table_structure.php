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
            // Rename existing columns to _bn
            $table->renameColumn('name', 'name_bn');
            $table->renameColumn('father_name', 'father_name_bn');
            $table->renameColumn('mother_name', 'mother_name_bn');
            $table->renameColumn('profession', 'occupation_bn');
            $table->renameColumn('dob', 'date_of_birth');
        });

        Schema::table('voters', function (Blueprint $table) {
            // Add missing _en columns if they don't exist
            if (!Schema::hasColumn('voters', 'occupation_en')) {
                $table->string('occupation_en')->nullable()->after('occupation_bn');
            }
            if (!Schema::hasColumn('voters', 'spouse_name_bn')) {
                $table->string('spouse_name_bn')->nullable()->after('mother_name_en');
            }
            if (!Schema::hasColumn('voters', 'spouse_name_en')) {
                $table->string('spouse_name_en')->nullable()->after('spouse_name_bn');
            }
            
            // Drop old columns we don't need
            $table->dropColumn(['address', 'address_en', 'union_ward', 'upazila', 'district']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voters', function (Blueprint $table) {
            // Reverse the changes
            $table->renameColumn('name_bn', 'name');
            $table->renameColumn('father_name_bn', 'father_name');
            $table->renameColumn('mother_name_bn', 'mother_name');
            $table->renameColumn('occupation_bn', 'profession');
            $table->renameColumn('date_of_birth', 'dob');
            
            $table->dropColumn(['occupation_en', 'spouse_name_bn', 'spouse_name_en']);
            
            $table->text('address')->nullable();
            $table->text('address_en')->nullable();
            $table->string('union_ward')->nullable();
            $table->string('upazila')->nullable();
            $table->string('district')->nullable();
        });
    }
};
