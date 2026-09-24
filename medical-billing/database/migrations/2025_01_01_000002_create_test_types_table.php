<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // e.g. "General Medical", "Remedial Medical"
            $table->decimal('fee', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed the two known types from the flowchart so the app is usable immediately
        DB::table('test_types')->insert([
            ['name' => 'General Medical', 'fee' => 1500.00, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Remedial Medical', 'fee' => 2000.00, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('test_types');
    }
};
