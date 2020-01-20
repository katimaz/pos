<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('template_name', 60);
            $table->char('template_remark', 255);
            $table->char('order_type',12)->default('S');
            $table->char('order_no', 60)->nullable();
            $table->date('order_date')->nullable();
            $table->char('separate',12)->nullable();
            $table->integer('customer_id')->nullable();
            $table->char('customer_country_name', 12)->nullable();
            $table->char('customer_name', 40)->nullable();
            $table->char('customer_address', 255)->nullable();
            $table->char('customer_delivery_address', 255)->nullable();
            $table->char('customer_phone', 60)->nullable();
            $table->char('customer_remark', 255)->nullable();
            $table->char('referral', 30)->nullable();
            $table->char('total_price', 60);
            $table->char('order_currency',12)->nullable();
            $table->char('order_currency_ratio',12)->nullable();
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
        Schema::dropIfExists('template_orders');
    }
}
