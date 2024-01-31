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
        Schema::table('sale_details', function (Blueprint $table) {
            //
            $table->float('disc',2,2)->nullable()->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_details', function (Blueprint $table) {
            //
            if(Schema::hasColumn('sale_details', 'disc')){
                $table->dropColumn('disc');
            }
        });
    }
};