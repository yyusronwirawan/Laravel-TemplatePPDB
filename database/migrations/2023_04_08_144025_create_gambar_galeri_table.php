<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGambarGaleriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gambar_galeri', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bagaian_kata_galeri_id');
            $table->text('image');
            $table->timestamps();

            $table->foreign('bagaian_kata_galeri_id')->references('id')
                ->on('bagaian_kata_galeri')
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
        Schema::dropIfExists('gambar_galeri');
    }
}
