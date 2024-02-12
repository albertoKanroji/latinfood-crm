<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lotes', function (Blueprint $table) {
           
  $table->id();
            $table->string('Nombre_Lote',255);
            $table->integer('CodigoBarras');
            $table->integer('Cantidad_Articulos');
            $table->date('Fecha_Expedicion');
            $table->date('Fecha_Vencimiento');



 $table->unsignedBigInteger('SKU');
 $table->foreign('SKU')->references('id')->on('products');

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
        Schema::dropIfExists('lotes');
    }
}
