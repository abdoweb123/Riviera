<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressTable extends Migration
{
    public function up()
    {
        Schema::create('address', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->unsignedBigInteger('client_id');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->longText('note')->nullable();

            $table->foreign('client_id')->on('clients')->references('id')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('address');
    }
}
