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
        Schema::table('scars', function (Blueprint $table) {
            $table->text('cancelled_by')->nullable();
            $table->text('cancelled_on')->nullable();
            $table->longText('cancelled_comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scars', function (Blueprint $table) {
            //
        });
    }
};
