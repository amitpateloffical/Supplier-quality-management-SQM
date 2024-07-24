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
        Schema::table('suppliers', function (Blueprint $table) {
            $table->text('request_justified_by')->nullable();
            $table->text('request_justified_on')->nullable();
            $table->longText('request_justified_comment')->nullable();

            $table->text('cqa_review_by')->nullable();
            $table->text('cqa_review_on')->nullable();
            $table->longText('cqa_review_comment')->nullable();

            $table->text('purchase_sample_initiated_by')->nullable();
            $table->text('purchase_sample_initiated_on')->nullable();
            $table->longText('purchase_sample_initiated_comment')->nullable();

            $table->text('purchase_sample_satisfactory_by')->nullable();
            $table->text('purchase_sample_satisfactory_on')->nullable();
            $table->longText('purchase_sample_satisfactory_comment')->nullable();

            $table->text('FD_review_by')->nullable();
            $table->text('FD_review_on')->nullable();
            $table->longText('FD_review_comment')->nullable();

            $table->text('cqa_final_review_by')->nullable();
            $table->text('cqa_final_review_on')->nullable();
            $table->longText('cqa_final_review_comment')->nullable();

            // Backword Stage

            $table->text('requestedTo_pendig_review_by')->nullable();
            $table->text('requestedTo_pendig_review_on')->nullable();
            $table->longText('requestedTo_pendig_review_comment')->nullable();

            $table->text('requestedTo_pendingCQA_by')->nullable();
            $table->text('requestedTo_pendingCQA_on')->nullable();
            $table->longText('requestedTo_pendingCQA_comment')->nullable();

            $table->text('requestedTo_pendingPurchase_by')->nullable();
            $table->text('requestedTo_pendingPurchase_on')->nullable();
            $table->longText('requestedTo_pendingPurchase_comment')->nullable();

            $table->text('requestedTo_pendingFromCQA_by')->nullable();
            $table->text('requestedTo_pendingFromCQA_on')->nullable();
            $table->longText('requestedTo_pendingFromCQA_comment')->nullable();

            $table->text('requestedTo_pendingInitiating_by')->nullable();
            $table->text('requestedTo_pendingInitiating_on')->nullable();
            $table->longText('requestedTo_pendingInitiating_comment')->nullable();

            $table->text('requestedTo_opened_by')->nullable();
            $table->text('requestedTo_opened_on')->nullable();
            $table->longText('requestedTo_opened_comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            //
        });
    }
};
