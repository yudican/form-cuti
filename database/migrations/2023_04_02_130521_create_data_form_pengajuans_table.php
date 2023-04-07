<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_form_pengajuan', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal_berangkat')->nullable();
            $table->date('tanggal_kembali')->nullable();
            $table->string('tujuan')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('file_kesehatan')->nullable();
            $table->string('file_jasmani')->nullable();
            $table->enum('status', ['draft', 'diusulkan', 'disetujui', 'ditolak'])->nullable()->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_form_pengajuan');
    }
};
