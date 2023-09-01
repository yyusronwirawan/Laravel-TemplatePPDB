<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalSiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jadwal_siswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kegiatan_akademik_siswa_id');
            $table->unsignedBigInteger('mata_pelajaran_id');
            $table->unsignedBigInteger('ekstrakurikuler_id')->nullable();
            $table->timestamps();

            $table->foreign('kegiatan_akademik_siswa_id')
                ->references('id')
                ->on('kegiatan_akademik_siswa')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('mata_pelajaran_id')
                ->references('id')
                ->on('mata_pelajaran')
                ->onUpdate('cascade')
                ->onDelete('cascade');


            $table->foreign('ekstrakurikuler_id')
                ->references('id')
                ->on('ekstrakurikuler')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jadwal_siswa');
    }
}
