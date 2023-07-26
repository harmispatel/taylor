<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_details', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->text('category_id');
            $table->string('model_size')->nullable();
            $table->string('brand')->nullable();
            $table->string('purchase_link')->nullable();
            $table->string('tylormade_id')->nullable();
            $table->integer('target_id')->nullable();
            $table->integer('upload_id')->nullable();
            $table->integer('album_id')->nullable();
            $table->string('height')->nullable();
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
        Schema::dropIfExists('model_details');
    }
}
