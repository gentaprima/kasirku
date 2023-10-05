<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TblProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_product',function(Blueprint $table){
            $table->id();
            $table->string('product_name');
            $table->string('product_category');
            $table->integer('price');
            $table->integer('is_active');
            $table->string('photo')->nullable();
            $table->string('group');
            $table->integer('stock_reduction');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("tbl_product");
    }
}
