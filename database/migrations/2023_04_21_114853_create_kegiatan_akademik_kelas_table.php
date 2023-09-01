<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKegiatanAkademikKelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kegiatan_akademik_kelas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kalender_akademik_id');
            $table->unsignedBigInteger('kelas_id');
            $table->unsignedBigInteger('wali_kelas_id');
            $table->string('status');
            $table->timestamps();

            $table->foreign('kalender_akademik_id')->references('id')
                ->on('kalender_akademik')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('kelas_id')->references('id')
                ->on('kelas')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('wali_kelas_id')->references('id')
                ->on('guru')
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
        Schema::dropIfExists('kegiatan_akademik_kelas');
    }
}
