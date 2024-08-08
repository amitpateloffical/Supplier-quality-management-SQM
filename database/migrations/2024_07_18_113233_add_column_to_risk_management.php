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
            $table->text('action_plan_approved_by')->nullable();
            $table->text('action_plan_approved_on')->nullable();
            $table->longText('action_plan_approved_comment')->nullable();
            $table->text('all_action_completed_by')->nullable();
            $table->text('all_action_completed_on')->nullable();
            $table->longText('all_action_completed_comment')->nullable();
            $table->text('residual_risk_completed_by')->nullable();
            $table->text('residual_risk_completed_on')->nullable();
            $table->longText('residual_risk_completed_comment')->nullable();
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
