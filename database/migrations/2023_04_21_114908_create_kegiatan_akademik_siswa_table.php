<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKegiatanAkademikSiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kegiatan_akademik_siswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kegiatan_akademik_kelas_id');
            $table->unsignedBigInteger('siswa_id');
            $table->string('status');
            $table->timestamps();

            $table->foreign('kegiatan_akademik_kelas_id')->references('id')
                ->on('kegiatan_akademik_kelas')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('siswa_id')->references('id')
                ->on('siswa')
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
        Schema::dropIfExists('kegiatan_akademik_siswa');
    }
}
