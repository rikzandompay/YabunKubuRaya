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
        Schema::create('foto_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatan')->cascadeOnDelete();
            $table->string('jalur_foto', 100);
            $table->string('keterangan', 100)->nullable();
            $table->boolean('adalah_sampul')->default(false);
            $table->timestamp('dibuat_pada')->nullable()->useCurrent();
            $table->timestamp('diperbarui_pada')->nullable()->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_kegiatan');
    }
};
