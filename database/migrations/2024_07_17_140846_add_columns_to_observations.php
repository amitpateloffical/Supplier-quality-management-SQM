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
        Schema::table('observations', function (Blueprint $table) {
            $table->string('Completed_Comment')->nullable();
            $table->string('QA_Approved_Comment')->nullable();
            $table->string('Final_Approval_Comment')->nullable();
            $table->string('Report_Issued_By')->nullable();
            $table->string('Report_Issued_On')->nullable();
            $table->string('Report_Issued_Comment')->nullable();
            $table->string('Cancelled_By')->nullable();
            $table->string('Cancelled_On')->nullable();
            $table->string('Cancelled_Comment')->nullable();
            $table->string('Reject_CAPA_Plan_By')->nullable();
            $table->string('Reject_CAPA_Plan_On')->nullable();
            $table->string('Reject_CAPA_Plan_Comment')->nullable();
            $table->string('Reject_CAPA_Plan_By1')->nullable();
            $table->string('Reject_CAPA_Plan_On1')->nullable();
            $table->string('Reject_CAPA_Plan_Comment1')->nullable();
            $table->string('QA_Approval_Without_CAPA_By')->nullable();
            $table->string('QA_Approval_Without_CAPA_On')->nullable();
            $table->string('QA_Approval_Without_CAPA_Comment')->nullable();
            $table->string('All_CAPA_Closed_By')->nullable();
            $table->string('All_CAPA_Closed_On')->nullable();
            $table->string('All_CAPA_Closed_Comment')->nullable();

            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('observations', function (Blueprint $table) {
            //
        });
    }
};
