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
        Schema::table('deviations', function (Blueprint $table) {

            //stage 1
            //$table->text('submit_on')->nullable();
            //$table->text('submit_by')->nullable();
            //$table->longText('submit_comment')->nullable();

            //$table->text('cancelled_by')->nullable();
            //$table->text('cancelled_on')->nullable();
            $table->longText('cancelled_comments')->nullable();

            //stage 2
            //$table->text('HOD_Review_Complete_By')->nullable();
            //$table->text('HOD_Review_Complete_On')->nullable();
            //$table->longText('HOD_Review_Comments')->nullable();

            $table->text('Hod_cancelled_by')->nullable();
            $table->text('Hod_cancelled_on')->nullable();
            $table->longText('Hod_cancelled_comments')->nullable();

            $table->text('hod_more_info_required_by')->nullable();
            $table->text('hod_more_info_required_on')->nullable();
            $table->longText('hod_more_info_required_comments')->nullable();

            //stage 3
            // $table->text('QA_Initial_Review_Complete_By')->nullable();
            // $table->text('QA_Initial_Review_Complete_On')->nullable();
            // $table->longText('QA_Initial_Review_Comments')->nullable();

            //$table->text('qa_more_info_required_by')->nullable();
            //$table->text('qa_more_info_required_on')->nullable();
            $table->longText('qa_more_info_required_comments')->nullable();

            $table->text('CFT_Review_Not_Required_By')->nullable();
            $table->text('CFT_Review_Not_Required_On')->nullable();
            $table->longText('CFT_Review_Not_Required_Comments')->nullable();

            //stage 4
            //$table->text('CFT_Review_Complete_By')->nullable();
            //$table->text('CFT_Review_Complete_On')->nullable();
            //$table->longText('CFT_Review_Comments')->nullable();

            $table->text('cft_more_info_required_by')->nullable();
            $table->text('cft_more_info_required_on')->nullable();
            $table->longText('cft_more_info_required_comments')->nullable();

            //stage 5
            $table->text('QA_Secondary_Review_Complete_By')->nullable();
            $table->text('QA_Secondary_Review_Complete_On')->nullable();
            $table->longText('QA_Secondary_Review_Completed_Comments')->nullable();

            $table->text('send_to_opened_by')->nullable();
            $table->text('send_to_opened_on')->nullable();
            $table->longText('send_to_opened_comments')->nullable();

            $table->text('QA_Secondary_Send_to_Hod_By')->nullable();
            $table->text('QA_Secondary_Send_to_Hod_On')->nullable();
            $table->longText('QA_Secondary_Send_to_Hod_Comments')->nullable();

            $table->text('Send_to_QA_Initial_Review_By')->nullable();
            $table->text('Send_to_QA_Initial_Review_On')->nullable();
            $table->longText('Send_to_QA_Initial_Review_Comments')->nullable();

            //stage 6
            //$table->text('QAH_Primary_Approval_Completed_By')->nullable();
            //$table->text('QAH_Primary_Approval_Completed_On')->nullable();
            //$table->longText('QAH_Primary_Approval_Completed_Comments')->nullable();

            $table->text('QAH_More_Information_Required_By')->nullable();
            $table->text('QAH_More_Information_Required_On')->nullable();
            $table->longText('QAH_More_Information_Required_Comments')->nullable();

            //stage 7

            $table->text('Initiator_Update_Completed_By')->nullable();
            $table->text('Initiator_Update_Completed_On')->nullable();
            $table->longText('Initiator_Update_Completed_Comments')->nullable();

            $table->text('Send_to_initialStage_By')->nullable();
            $table->text('Send_to_initialStage_On')->nullable();
            $table->longText('Send_to_initialStage_Comments')->nullable();

            $table->text('Send_to_Hod_By')->nullable();
            $table->text('Send_to_Hod_On')->nullable();
            $table->longText('Send_to_Hod_Comments')->nullable();

            $table->text('Send_to_QA_Initial_By')->nullable();
            $table->text('Send_to_QA_Initial_On')->nullable();
            $table->longText('Send_to_QA_Initial_Comments')->nullable();

            //stage8

            $table->text('HOD_Final_Send_to_Opened_By')->nullable();
            $table->text('HOD_Final_Send_to_Opened_On')->nullable();
            $table->longText('HOD_Final_Send_to_Opened_Comments')->nullable();

            $table->text('HOD_Final_Send_to_Initiator_By')->nullable();
            $table->text('HOD_Final_Send_to_Initiator_On')->nullable();
            $table->longText('HOD_Final_Send_to_Initiator_Comments')->nullable();

            //stage9
            // $table->longText('QA_Final_Review_Comments')->nullable();

            $table->text('QA_Final_Send_to_Opened_By')->nullable();
            $table->text('QA_Final_Send_to_Opened_On')->nullable();
            $table->longText('QA_Final_Send_to_Opened_Comments')->nullable();

            $table->text('QA_Final_Send_to_HOD_By')->nullable();
            $table->text('QA_Final_Send_to_HOD_On')->nullable();
            $table->longText('QA_Final_Send_to_HOD_Comments')->nullable();

            $table->text('Send_to_QA_Initiator_By')->nullable();
            $table->text('Send_to_QA_Initiator_On')->nullable();
            $table->longText('Send_to_QA_Initiator_Comments')->nullable();


            //stage 10

            //$table->text('QA_Final_Approved_By')->nullable();
            //$table->text('QA_Final_Approved_On')->nullable();
            //$table->longText('QA_Final_Approved_Comments')->nullable();

            $table->text('QA_Approval_Send_to_Opened_By')->nullable();
            $table->text('QA_Approval_Send_to_Opened_On')->nullable();
            $table->longText('QA_Approval_Send_to_Opened_Comments')->nullable();

            $table->text('QA_Approval_Send_to_HOD_By')->nullable();
            $table->text('QA_Approval_Send_to_HOD_On')->nullable();
            $table->longText('QA_Approval_Send_to_HOD_Comments')->nullable();

            $table->text('Approval_Send_to_QA_Initial_By')->nullable();
            $table->text('Approval_Send_to_QA_Initial_On')->nullable();
            $table->longText('Approval_Send_to_QA_Initial_Comments')->nullable();

            $table->text('Send_to_Pending_Initiator_Updated_By')->nullable();
            $table->text('Send_to_Pending_Initiator_Updated_On')->nullable();
            $table->longText('Send_to_Pending_Initiator_Updated_Comments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deviations', function (Blueprint $table) {
            //
        });
    }
};
