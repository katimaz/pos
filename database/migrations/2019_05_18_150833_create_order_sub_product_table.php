<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderSubProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_sub_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_product_id');
            $table->integer('sub_product_id');
            $table->char('sub_product_name', 255);
            $table->integer('sub_product_quantity');
            $table->char('sub_product_price',60);
            $table->char('sub_product_total_price',60);
            $table->string('sub_product_model_no', 255)->nullable();
            $table->string('sub_product_serial_no', 255)->nullable();
            $table->string('sub_product_remark', 255)->nullable();
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
        Schema::dropIfExists('order_sub_products');
    }
}
