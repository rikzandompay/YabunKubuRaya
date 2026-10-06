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
        Schema::create('gallery_photos', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Judul/deskripsi foto (digunakan untuk atribut alt)');
            $table->string('image_path')->comment('Path lokasi file foto di storage');
            $table->enum('category', ['jumat_berkah', 'donasi', 'dzikir'])->default('jumat_berkah')->comment('Kategori foto galeri');
            $table->boolean('is_featured')->default(false)->comment('Penanda foto utama/landscape besar');
            $table->integer('sort_order')->default(0)->comment('Urutan tampil foto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_photos');
    }
};
