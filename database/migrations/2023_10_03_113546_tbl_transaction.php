<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TblTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_transaction',function(Blueprint $table){
            $table->id();
            $table->string('order_id');
            $table->integer('total');
            $table->date('date');
            $table->timestamps();
            $table->unsignedBigInteger("id_cart")->unsigned();
            $table->foreign("id_cart")->references("id")->on("tbl_cart")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("tbl_transaction");
    }
}
