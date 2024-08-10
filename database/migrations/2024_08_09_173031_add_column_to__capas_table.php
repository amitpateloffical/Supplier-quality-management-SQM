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
        Schema::table('capas', function (Blueprint $table) {
            $table->string('reject_more_info_requierd_by')->nullable();
            $table->string('reject_more_info_requierd_on')->nullable();
            $table->longText('reject_more_info_requierd_comment')->nullable();
            $table->longText('approved_comment')->nullable();
            $table->longText('completedd_comment')->nullable();
            $table->longText('cancelled_comment')->nullable();
            $table->longText('qa_more_info_required_comment')->nullable();
            $table->longText('plan_approved_comment')->nullable();
            $table->longText('plan_proposed_comment')->nullable();
            $table->longText('more_info_review_comment')->nullable();
            $table->longText('all_actions_completed_comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('_capas', function (Blueprint $table) {
            //
        });
    }
};
