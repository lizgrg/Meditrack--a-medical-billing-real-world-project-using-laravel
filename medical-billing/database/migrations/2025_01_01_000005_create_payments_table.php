<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            // one-to-one: each invoice gets exactly one full payment (per Phase 1 decision)
            $table->foreignId('invoice_id')->unique()->constrained('invoices');
            $table->enum('payment_method', ['cash', 'cheque', 'online']);
            $table->decimal('amount', 10, 2);

            // Cheque-specific
            $table->string('cheque_number')->nullable();
            $table->string('cheque_bank')->nullable();

            // Online-specific
            $table->string('transaction_id')->nullable();

            $table->foreignId('received_by')->constrained('users');
            $table->date('payment_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
