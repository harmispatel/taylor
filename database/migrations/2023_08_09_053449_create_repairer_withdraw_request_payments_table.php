<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepairerWithdrawRequestPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repairer_withdraw_request_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('repairer_id')->nullable();
            $table->bigInteger('withdraw_request_id')->nullable();
            $table->double('requested_amount')->nullable();
            $table->double('paid_to_admin_amount')->nullable();
            $table->double('paid_to_repairer_amount')->nullable();
            $table->string('paid_by')->nullable();
            $table->string('payment_option')->nullable();
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
        Schema::dropIfExists('repairer_withdraw_request_payments');
    }
}
