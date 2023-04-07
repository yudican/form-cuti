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
        Schema::table('data_form_pengajuan', function (Blueprint $table) {
            $table->string('nomor_sij')->nullable()->after('id');
            $table->string('tanggal_disetujui')->nullable()->after('tanggal_kembali');
            $table->string('keperluan')->nullable()->after('tujuan');
            $table->string('pengikut')->nullable()->after('keperluan');
            $table->string('transportasi')->nullable()->after('pengikut');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_form_pengajuan', function (Blueprint $table) {
            $table->dropColumn('nomor_sij');
            $table->dropColumn('tanggal_disetujui');
            $table->dropColumn('keperluan');
            $table->dropColumn('pengikut');
            $table->dropColumn('transportasi');
        });
    }
};
