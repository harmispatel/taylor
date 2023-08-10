<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryStoreAvailablityHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_store_availablity_hours', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('delivery_store_id')->nullable();
            $table->string('days')->nullable();
            $table->time('from_time')->nullable();
            $table->time('to_time')->nullable();
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
        Schema::dropIfExists('delivery_store_availablity_hours');
    }
}
