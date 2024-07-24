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
        Schema::table('risk_management', function (Blueprint $table) {
            $table->text('submitted_comment')->nullable();
            $table->text('evaluated_comment')->nullable();
            $table->text('plan_approved_comment')->nullable();
            $table->text('cancelled_comment')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('risk_management', function (Blueprint $table) {
            //
        });
    }
};
