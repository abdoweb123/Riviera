<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->nullable()->on('clients')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('employer_id')->nullable();
            $table->foreign('employer_id')->nullable()->on('employers')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign('admin_id')->nullable()->on('admins')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->string('title_ar')->nullable();
            $table->string('title_en')->nullable();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->nullable()->on('orders')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->foreign('branch_id')->nullable()->on('branches')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
