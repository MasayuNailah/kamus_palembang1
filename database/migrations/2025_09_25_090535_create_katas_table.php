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
        Schema::create('tb_kata', function (Blueprint $table) {
            $table->string('id_kata')->primary();
            $table->string('sub_id')->nullable();
            $table->string('kata');
            $table->text('arti');
            $table->string('cara_baca')->nullable();
            $table->string('keterangan')->nullable();
            $table->enum('status_validasi', ['draft', 'valid'])->default('draft');
            $table->unsignedBigInteger('id_kontributor')->nullable();
            $table->unsignedBigInteger('id_validator')->nullable();
            $table->timestamps();

            // Foreign key untuk relasi ke kata itu sendiri (sub_id)
            $table->foreign('sub_id')->references('id_kata')->on('tb_kata')->onDelete('set null');

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
        Schema::dropIfExists('tb_kata');
    }
};