<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::create('store_settings', function (Blueprint $table) {
            $table->id();

            $table->string('store_name', 100);
            $table->string('address', 255)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();

            $table->string('logo')->nullable();
            $table->string('npwp', 50)->nullable();

            $table->text('footer_note')->nullable();
            $table->text('footer_message')->nullable();

            $table->boolean('show_logo')->default(true);
            $table->boolean('show_address')->default(true);
            $table->boolean('show_phone')->default(true);
            $table->boolean('show_qris')->default(false);

            $table->string('qris_image')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('store_settings');
    }
};
