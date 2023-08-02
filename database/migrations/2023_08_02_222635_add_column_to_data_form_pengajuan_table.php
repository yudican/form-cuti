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
            $table->string('jenis')->default('CUTI')->after('status');
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
            $table->dropColumn('jenis');
        });
    }
};
