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
        Schema::create('tb_kalimat', function (Blueprint $table) {
            $table->string('id_kalimat')->primary();
            $table->string('sub_id');
            $table->text('kalimat');
            $table->text('arti');
            $table->enum('status_validasi', ['draft', 'valid'])->default('draft');
            $table->unsignedBigInteger('id_kontributor')->nullable();
            $table->unsignedBigInteger('id_validator')->nullable();
            $table->timestamps();

            // Foreign key untuk relasi ke tabel kata
            $table->foreign('sub_id')->references('sub_id')->on('tb_kata')->onDelete('cascade');
            
            // Foreign keys untuk relasi ke tabel users
            $table->foreign('id_kontributor')->references('id_user')->on('users')->onDelete('set null');
            $table->foreign('id_validator')->references('id_user')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_kalimat');
    }
};