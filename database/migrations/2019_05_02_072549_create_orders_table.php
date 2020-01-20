<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('order_type',12);
            $table->char('order_no', 60);
            $table->date('order_date');
            $table->char('separate',12)->nullable();
            $table->integer('customer_id');
            $table->char('customer_country_name', 12);
            $table->char('customer_name', 40);
            $table->char('customer_address', 255);
            $table->char('customer_delivery_address', 255)->nullable();
            $table->char('customer_phone', 60);
            $table->char('customer_remark', 255)->nullable();
            $table->char('referral', 30)->nullable();
            $table->char('total_price', 60);
            $table->char('order_currency',12);
            $table->char('order_currency_ratio',12);
            $table->boolean('status')->default(1);
            $table->string('created_by');
            $table->string('updated_by');
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
        Schema::dropIfExists('orders');
    }
}
