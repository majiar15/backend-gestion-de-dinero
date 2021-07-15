<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Historial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('operacion');
            $table->string('mensaje');
            $table->bigInteger('valor');
            $table->bigInteger('cartera_id')->unsigned();
            $table->foreign('cartera_id')->references('id')->on('carteras');
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
        Schema::dropIfExists('history');
    }
}
