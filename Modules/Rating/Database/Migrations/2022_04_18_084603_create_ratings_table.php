<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->unsignedBigInteger('client_id'); // User giving the rating
            $table->unsignedBigInteger('driver_id'); // Driver being rated
            $table->unsignedInteger('rating'); // Rating value
            $table->longText('comment'); // comment
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('ratings');
    }

}
