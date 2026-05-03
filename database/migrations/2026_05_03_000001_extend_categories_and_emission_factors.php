<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Alter categories.unit from enum to string to support more unit types
        DB::statement("ALTER TABLE categories MODIFY unit VARCHAR(30) DEFAULT 'spend'");

        // 2. Add category_id + metadata to emission_factors
        Schema::table('emission_factors', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('id')
                  ->constrained()->nullOnDelete();
            $table->json('metadata')->nullable()->after('source'); // extra data like watt, default_kwh
        });
    }

    public function down(): void
    {
        Schema::table('emission_factors', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id', 'metadata']);
        });
        DB::statement("ALTER TABLE categories MODIFY unit ENUM('spend', 'distance') DEFAULT 'spend'");
    }
};
