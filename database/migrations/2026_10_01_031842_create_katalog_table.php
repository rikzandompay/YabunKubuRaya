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
        Schema::create('katalog', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('program')->cascadeOnDelete();
            $table->string('judul', 100);
            $table->string('slug', 120)->unique();
            $table->text('deskripsi');
            $table->string('gambar', 100)->nullable();
            $table->string('target_penerima', 50)->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamp('dibuat_pada')->nullable()->useCurrent();
            $table->timestamp('diperbarui_pada')->nullable()->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('katalog');
    }
};
