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
        Schema::create('donasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donatur_id')->nullable()->constrained('donatur')->nullOnDelete();
            $table->foreignId('rekening_bank_id')->nullable()->constrained('rekening_bank')->nullOnDelete();
            $table->string('kode_donasi', 50)->unique();
            $table->enum('kategori_donasi', ['jumat_berkah', 'uang_donasi', 'uang_pembangunan'])->index();
            $table->decimal('jumlah_donasi', 15, 2);
            $table->date('tanggal_donasi')->index();
            $table->enum('metode_pembayaran', ['transfer_bank', 'qris', 'tunai'])->default('transfer_bank');
            $table->string('bukti_transfer')->nullable();
            $table->enum('status', ['menunggu', 'diverifikasi', 'ditolak'])->default('diverifikasi')->index();
            $table->text('catatan_doa')->nullable();
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamp('dibuat_pada')->nullable()->useCurrent();
            $table->timestamp('diperbarui_pada')->nullable()->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donasi');
    }
};
