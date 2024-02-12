<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
              $table->id();
             $table->string('name',255);
                 $table->string('last_name',255);
                     $table->string('last_name2',255);
                         $table->string('email',255);
                             $table->string('password',255);
                                 $table->string('address',255);
                                     $table->string('phone',255);
                                         $table->string('document',255);
                                             $table->integer('saldo');
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
        Schema::dropIfExists('customers');
    }
}
