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
            $table->text('analysis_more_info_by')->nullable();
            $table->text('analysis_more_info_on')->nullable();
            $table->text('analysis_more_info_comment')->nullable();
            $table->text('request_more_info_by')->nullable();
            $table->text('request_more_info_on')->nullable();
            $table->text('request_more_info_comment')->nullable();
            $table->text('reject_action_by')->nullable();
            $table->text('reject_action_on')->nullable();
            $table->text('reject_action_comment')->nullable();
            $table->text('action_request_action_by')->nullable();
            $table->text('action_request_action_on')->nullable();
            $table->text('action_request_action_comment')->nullable();
            $table->text('more_action_needed_by')->nullable();
            $table->text('more_action_needed_on')->nullable();
            $table->text('more_action_needed_comment')->nullable();
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
