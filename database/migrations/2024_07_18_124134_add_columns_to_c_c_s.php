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
        Schema::table('c_c_s', function (Blueprint $table) {
            $table->text('submitted_by')->nullable();
            $table->text('submitted_on')->nullable();
            $table->longText('submitted_comment')->nullable();

            $table->text('hod_review_completed_by')->nullable();
            $table->text('hod_review_completed_on')->nullable();
            $table->longText('hod_review_completed_comment')->nullable();

            $table->text('cft_review_by')->nullable();
            $table->text('cft_review_on')->nullable();
            $table->longText('cft_review_comment')->nullable();

            $table->text('cftNot_required_by')->nullable();
            $table->text('cftNot_required_on')->nullable();
            $table->longText('cftNot_required_comment')->nullable();

            $table->text('QA_review_completed_by')->nullable();
            $table->text('QA_review_completed_on')->nullable();
            $table->longText('QA_review_completed_comment')->nullable();

            $table->text('implemented_by')->nullable();
            $table->text('implemented_on')->nullable();
            $table->longText('implemented_comment')->nullable();

            $table->text('requested_to_hod_by')->nullable();
            $table->text('requested_to_hod_on')->nullable();
            $table->longText('requested_to_hod_comment')->nullable();

            $table->text('sent_to_opened_by')->nullable();
            $table->text('sent_to_opened_on')->nullable();
            $table->longText('sent_to_opened_comment')->nullable();

            $table->text('requestToHod_fromCft_by')->nullable();
            $table->text('requestToHod_fromCft_on')->nullable();
            $table->longText('requestToHod_fromCft_comment')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('c_c_s', function (Blueprint $table) {
            //
        });
    }
};
