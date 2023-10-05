<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TblCart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_cart',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('id_product')->unsigned();
            $table->unsignedBigInteger("id_users")->unsigned();
            $table->string('topping');
            $table->integer('quantity');
            $table->date('date');
            $table->integer('is_order');
            $table->integer('total');
            $table->timestamps();
            $table->foreign("id_product")->references("id")->on("tbl_product")->onDelete("cascade");
            $table->foreign("id_users")->references("id")->on("tbl_users")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("tbl_cart");
    }
}
