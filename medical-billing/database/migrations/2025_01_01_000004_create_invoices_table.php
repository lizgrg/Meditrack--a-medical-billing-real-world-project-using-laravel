<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique(); // e.g. INV-000001
            $table->foreignId('candidate_id')->constrained('candidates');
            $table->foreignId('test_type_id')->constrained('test_types');
            $table->decimal('amount', 10, 2); // snapshot of fee at time of billing
            $table->enum('status', ['unpaid', 'paid', 'cancelled'])->default('unpaid');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
