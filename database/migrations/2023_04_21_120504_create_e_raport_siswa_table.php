<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateERaportSiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('e_raport_siswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kalender_akademik_id');
            $table->unsignedBigInteger('kelas_id');
            $table->unsignedBigInteger('mata_pelajaran_id');
            $table->integer('nilai');
            $table->string('status');
            $table->timestamps();

            $table->foreign('kalender_akademik_id')
                ->references('id')
                ->on('kalender_akademik')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('kelas_id')
                ->references('id')
                ->on('kelas')
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
        Schema::dropIfExists('e_raport_siswa');
    }
}
