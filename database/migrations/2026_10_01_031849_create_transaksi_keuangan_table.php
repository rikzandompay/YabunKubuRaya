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
        Schema::create('transaksi_keuangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donasi_id')->nullable()->constrained('donasi')->nullOnDelete();
            $table->foreignId('dicatat_oleh')->constrained('pengguna')->restrictOnDelete();
            $table->string('nomor_referensi', 60)->unique();
            $table->enum('jenis_transaksi', ['pemasukan', 'pengeluaran'])->index();
            $table->enum('kategori', ['jumat_berkah', 'uang_donasi', 'uang_pembangunan', 'operasional', 'penyaluran', 'lainnya'])->index();
            $table->decimal('jumlah', 15, 2);
            $table->date('tanggal_transaksi')->index();
            $table->text('keterangan');
            $table->string('bukti_transaksi')->nullable();
            $table->timestamp('dibuat_pada')->nullable()->useCurrent();
            $table->timestamp('diperbarui_pada')->nullable()->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_keuangan');
    }
};
