<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverTable extends Migration
{
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->integer('points')->nullable();
            $table->string('uuid')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('image')->nullable();
            $table->string('phone_code')->nullable();
            $table->string('country_code')->nullable();
            $table->boolean('status')->default(1);
            $table->string('password')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('driver_device_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id');
            $table->foreign('driver_id')->on('drivers')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->longText('device_token')->nullable();

            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('driver_device_tokens');
        Schema::dropIfExists('drivers');
    }
}
