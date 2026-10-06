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
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            $table->string('kunci', 100)->unique();
            $table->longText('nilai')->nullable();
            $table->string('kelompok', 50)->default('umum')->index();
            $table->timestamp('dibuat_pada')->nullable()->useCurrent();
            $table->timestamp('diperbarui_pada')->nullable()->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan');
    }
};
