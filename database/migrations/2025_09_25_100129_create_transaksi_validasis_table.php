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
        Schema::create('transaksi_validasi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->string('id_objek');
            $table->enum('tipe_objek', ['kata', 'kalimat']);
            $table->unsignedBigInteger('id_kontributor')->nullable();
            $table->dateTime('tgl_kontribusi')->nullable();
            $table->unsignedBigInteger('id_validator')->nullable();
            $table->dateTime('tgl_validasi')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_kontributor')->references('id_user')->on('users')->onDelete('set null');
            $table->foreign('id_validator')->references('id_user')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_validasi');
    }
};