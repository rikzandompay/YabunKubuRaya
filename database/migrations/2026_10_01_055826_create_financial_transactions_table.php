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
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['pemasukan', 'pengeluaran'])->default('pemasukan')->index();
            $table->enum('category', ['jumat_berkah', 'donasi_bantuan', 'pembangunan_pondok_tahfidz'])->index();
            $table->decimal('amount', 15, 2);
            $table->foreignId('donor_id')->nullable()->constrained('donatur')->nullOnDelete();
            $table->text('description')->nullable();
            $table->date('transaction_date')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
    }
};
