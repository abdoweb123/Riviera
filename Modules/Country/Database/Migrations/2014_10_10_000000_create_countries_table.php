<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();

            $table->string('title_ar');
            $table->string('title_en');

            $table->string('currancy_code_ar')->nullable();
            $table->string('currancy_code_en')->nullable();

            $table->decimal('currancy_value', 5, 3)->default(0.000);
            $table->string('phone_code')->nullable();
            $table->string('country_code')->nullable();
            $table->integer('length')->default(10);
            $table->integer('decimals')->default(3);

            $table->string('lat')->nullable();
            $table->string('long')->nullable();

            $table->boolean('status')->default(1);
            $table->string('image')->nullable();

            $table->timestamps();
        });

        Schema::create('regions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->nullable()->on('countries')->references('id')->onDelete('cascade')->onUpdate('cascade');

            $table->string('title_ar')->nullable();
            $table->string('title_en')->nullable();

            $table->string('lat')->nullable();
            $table->string('long')->nullable();

            $table->boolean('status')->default(1);

            $table->timestamps();
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('region_id')->nullable();
            $table->foreign('region_id')->nullable()->on('regions')->references('id')->onDelete('cascade')->onUpdate('cascade');

            $table->string('title_ar')->nullable();
            $table->string('title_en')->nullable();

            $table->string('lat')->nullable();
            $table->string('long')->nullable();

            $table->boolean('status')->default(1);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cities');
        Schema::dropIfExists('regions');
        Schema::dropIfExists('countries');
    }
}
