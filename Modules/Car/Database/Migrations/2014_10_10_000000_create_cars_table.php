<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarTable extends Migration
{
    public function up()
    {
        Schema::create('car_types', function (Blueprint $table) {
            $table->id();

            $table->string('title_ar')->nullable();
            $table->string('title_en')->nullable();
            $table->string('image')->nullable();
            $table->integer('number')->nullable();
            $table->decimal('price',9,3)->nullable();

            $table->boolean('status')->default(1);

            $table->timestamps();
        });



        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar')->nullable();
            $table->string('title_en')->nullable();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedInteger('car_id');

            $table->boolean('status')->default(1);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('car_types');
        Schema::dropIfExists('cars');
    }
}
