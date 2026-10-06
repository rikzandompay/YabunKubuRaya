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
        Schema::create('artikel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->nullable()->constrained('program')->nullOnDelete();
            $table->foreignId('penulis_id')->constrained('pengguna')->cascadeOnDelete();
            $table->string('judul', 100);
            $table->string('slug', 100)->unique();
            $table->text('ringkasan')->nullable();
            $table->longText('isi_konten');
            $table->string('foto_header', 100)->nullable();
            $table->date('tanggal_publikasi')->index();
            $table->enum('status', ['draf', 'dipublikasikan'])->default('dipublikasikan')->index();
            $table->unsignedInteger('jumlah_dilihat')->default(0);
            $table->timestamp('dibuat_pada')->nullable()->useCurrent();
            $table->timestamp('diperbarui_pada')->nullable()->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artikel');
    }
};
