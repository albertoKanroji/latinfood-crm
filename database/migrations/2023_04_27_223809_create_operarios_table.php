<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',255);
            $table->string('apellido',255);
            $table->string('apellido2',255);
            $table->integer('edad');
            $table->string('compañia',255);
            $table->boolean('de_Planta');
            $table->unsignedBigInteger('id_envio');
            $table->foreign('id_envio')->references('id')->on('envios');

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
        Schema::dropIfExists('operarios');
    }
}
