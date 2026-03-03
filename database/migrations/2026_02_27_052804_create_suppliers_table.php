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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->dateTime('deleted_date')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->enum('is_deleted',['Y','N'])->default('N');
            $table->timestamps();
        });

        // Update tabel product_ins
        Schema::table('product_ins', function (Blueprint $table) {
            $table->foreignId('supplier_id')->nullable()->after('product_id')->constrained('suppliers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
