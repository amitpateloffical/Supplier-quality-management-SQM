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
            
            /****** Stage 1 ******/
            $table->text('submitted_by')->nullable();
            $table->text('submitted_on')->nullable();
            $table->longText('submitted_comment')->nullable();

            $table->text('cancelled_by')->nullable();
            $table->text('cancelled_on')->nullable();
            $table->longText('cancelled_comment')->nullable();

            $table->text('approvedBy_contract_giver_by')->nullable();
            $table->text('approvedBy_contract_giver_on')->nullable();
            $table->longText('approvedBy_contract_giver_comment')->nullable();

            $table->text('periodic_revoluation_by')->nullable();
            $table->text('periodic_revoluation_on')->nullable();
            $table->longText('periodic_revoluation_comment')->nullable();

            $table->text('manufacture_code_linked_by')->nullable();
            $table->text('manufacture_code_linked_on')->nullable();
            $table->longText('manufacture_code_linked_comment')->nullable();

            /****** Stage 2 ******/
            $table->text('request_not_justified_by')->nullable();
            $table->text('request_not_justified_on')->nullable();
            $table->longText('request_not_justified_comment')->nullable();

            $table->text('request_justified_by')->nullable();
            $table->text('request_justified_on')->nullable();
            $table->longText('request_justified_comment')->nullable();

            /****** Stage 3 ******/
            $table->text('prepurchase_sample_by')->nullable();
            $table->text('prepurchase_sample_on')->nullable();
            $table->longText('prepurchase_sample_comment')->nullable();

            $table->text('prepurchase_sample_notRequired_by')->nullable();
            $table->text('prepurchase_sample_notRequired_on')->nullable();
            $table->longText('prepurchase_sample_notRequired_comment')->nullable();

            $table->text('requestedTo_initiating_department_by')->nullable();
            $table->text('requestedTo_initiating_department_on')->nullable();
            $table->longText('requestedTo_initiating_department_comment')->nullable();

            /****** Stage 4 ******/
            $table->text('pendigPurchaseSampleRequested_by')->nullable();
            $table->text('pendigPurchaseSampleRequested_on')->nullable();
            $table->longText('pendigPurchaseSampleRequested_comment')->nullable();

            $table->text('requestedToPendingCQA_by')->nullable();
            $table->text('requestedToPendingCQA_on')->nullable();
            $table->longText('requestedToPendingCQA_comment')->nullable();

            /****** Stage 5 ******/
            $table->text('purchaseSampleanalysis_by')->nullable();
            $table->text('purchaseSampleanalysis_on')->nullable();
            $table->longText('purchaseSampleanalysis_comment')->nullable();

            $table->text('purchaseSampleanalysisNotSatisfactory_by')->nullable();
            $table->text('purchaseSampleanalysisNotSatisfactory_on')->nullable();
            $table->longText('purchaseSampleanalysisNotSatisfactory_comment')->nullable();

            /****** Stage 6 ******/
            $table->text('FdReviewCompleted_by')->nullable();
            $table->text('FdReviewCompleted_on')->nullable();
            $table->longText('FdReviewCompleted_comment')->nullable();

            $table->text('requestedToCQA_by')->nullable();
            $table->text('requestedToCQA_on')->nullable();
            $table->longText('requestedToCQA_comment')->nullable();

            /****** Stage 7 ******/
            $table->text('acknowledgByPD_by')->nullable();
            $table->text('acknowledgByPD_on')->nullable();
            $table->longText('acknowledgByPD_comment')->nullable();

            $table->text('reqquestedToFD_by')->nullable();
            $table->text('reqquestedToFD_on')->nullable();
            $table->longText('reqquestedToFD_comment')->nullable();

            /****** Stage 8 ******/
            $table->text('requirementFullfilled_by')->nullable();
            $table->text('requirementFullfilled_on')->nullable();
            $table->longText('requirementFullfilled_comment')->nullable();

            $table->text('requiredNotFulfilled_by')->nullable();
            $table->text('requiredNotFulfilled_on')->nullable();
            $table->longText('requiredNotFulfilled_comment')->nullable();

            $table->text('requestedToPD_by')->nullable();
            $table->text('requestedToPD_on')->nullable();
            $table->longText('requestedToPD_comment')->nullable();

            /****** Stage 10 ******/
            $table->text('riskRatingObservedAsHigh_by')->nullable();
            $table->text('riskRatingObservedAsHigh_on')->nullable();
            $table->longText('riskRatingObservedAsHigh_comment')->nullable();

            $table->text('riskRatingObservedAsLow_by')->nullable();
            $table->text('riskRatingObservedAsLow_on')->nullable();
            $table->longText('riskRatingObservedAsLow_comment')->nullable();
            
            $table->text('requestedToPendingCQAFinal_by')->nullable();
            $table->text('submitterequestedToPendingCQAFinal_on')->nullable();
            $table->longText('requestedToPendingCQAFinal_comment')->nullable();

            /****** Stage 11 ******/
            $table->text('manufacturerAuditPassed_by')->nullable();
            $table->text('manufacturerAuditPassed_on')->nullable();
            $table->longText('manufacturerAuditPassed_comment')->nullable();

            $table->text('requestedToPendigManufacturerRA_by')->nullable();
            $table->text('requestedToPendigManufacturerRA_on')->nullable();
            $table->longText('requestedToPendigManufacturerRA_comment')->nullable();

            /****** Stage 12 ******/
            $table->text('periodicRevolutionInitiated_by')->nullable();
            $table->text('periodicRevolutionInitiated_on')->nullable();
            $table->longText('periodicRevolutionInitiated_comment')->nullable();

            $table->text('requestedToPendingManufacturerAudit_by')->nullable();
            $table->text('requestedToPendingManufacturerAudit_on')->nullable();
            $table->longText('requestedToPendingManufacturerAudit_comment')->nullable();

            /****** Stage 13 ******/
            $table->text('riskRatingObservedAsHighMedium_by')->nullable();
            $table->text('riskRatingObservedAsHighMedium_on')->nullable();
            $table->longText('riskRatingObservedAsHighMedium_comment')->nullable();

            $table->text('riskRatingObservedLow_by')->nullable();
            $table->text('riskRatingObservedLow_on')->nullable();
            $table->longText('riskRatingObservedLow_comment')->nullable();

            /****** Stage 14 ******/
            $table->text('pendingManufacturerAuditFailed_by')->nullable();
            $table->text('pendingManufacturerAuditFailed_on')->nullable();
            $table->longText('pendingManufacturerAuditFailed_comment')->nullable();
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
