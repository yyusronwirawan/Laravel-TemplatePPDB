<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenulisBukuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penulis_buku', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penulis_id');
            $table->unsignedBigInteger('buku_id');
            $table->timestamps();

            $table->foreign('penulis_id')->references('id')
                ->on('penulis')
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
        Schema::dropIfExists('penulis_buku');
    }
}
