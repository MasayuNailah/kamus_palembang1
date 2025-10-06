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
        // This migration changes the foreign key on tb_kalimat.sub_id
        // to reference tb_kata.id_kata instead of tb_kata.sub_id.
        Schema::table('tb_kalimat', function (Blueprint $table) {
            // Drop existing FK if it exists. Use raw statement name if needed.
            try {
                $table->dropForeign(['sub_id']);
            } catch (\Exception $e) {
                // ignore if it doesn't exist
            }

            // Ensure column definition matches the referenced column type
            // here we assume id_kata is string; do not change column type to avoid data loss

            $table->foreign('sub_id')
                ->references('id_kata')
                ->on('tb_kata')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_kalimat', function (Blueprint $table) {
            try {
                $table->dropForeign(['sub_id']);
            } catch (\Exception $e) {
                // ignore
            }

            // Restore original FK pointing to tb_kata.sub_id
            $table->foreign('sub_id')
                ->references('sub_id')
                ->on('tb_kata')
                ->onDelete('cascade');
        });
    }
};
