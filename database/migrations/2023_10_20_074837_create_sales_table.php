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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('code_sale',10)->unique();
            $table->date('date');
            $table->text('information')->nullable();
            $table->bigInteger('customer_id', true);
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->decimal('cash_received', 15, 2)->default(0); // Uang yang diterima
    $table->decimal('cash_change', 15, 2)->default(0);   // Uang kembalian
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
        Schema::dropIfExists('sales');
    }
};
