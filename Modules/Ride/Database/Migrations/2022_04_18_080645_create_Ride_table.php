<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRideTable extends Migration
{
    public function up()
    {
        Schema::create('rides', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->unsignedBigInteger('address_id');
            $table->dateTime('date_time');

            $table->foreign('address_id')->on('address')->references('id')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rides');
    }
}
