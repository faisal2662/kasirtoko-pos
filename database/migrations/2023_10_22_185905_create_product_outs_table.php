<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_outs', function (Blueprint $table) {
            $table->id();
            $table->string('code_product_out',10)->unique();
            $table->string('sale_id',10);
            $table->foreign('sale_id')->references('code_sale')->on('sales');
            $table->string('product_id',10);
            $table->foreign('product_id')->references('code_product')->on('products');
            $table->date('date');
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
        Schema::dropIfExists('product_outs');
    }
};