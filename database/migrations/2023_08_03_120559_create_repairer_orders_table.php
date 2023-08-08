<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepairerOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repairer_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('customer_id')->nullable();
            $table->bigInteger('seller_id')->nullable();
            $table->bigInteger('repairer_id')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->string('measurement_id')->nullable();
            $table->bigInteger('service_id')->nullable();
            $table->decimal('service_cost')->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->text('image')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('payment_status')->default(1);
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
        Schema::dropIfExists('repairer_orders');
    }
}
