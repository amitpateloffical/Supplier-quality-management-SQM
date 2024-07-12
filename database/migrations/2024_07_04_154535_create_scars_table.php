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
        Schema::create('scars', function (Blueprint $table) {
            $table->id();
            $table->integer('record')->nullable();
            $table->integer('parent_id')->nullable();
            $table->text('parent_type')->nullable();
            $table->text('type')->nullable();
            $table->integer('division_id')->nullable();
            $table->integer('initiator_id')->nullable();
            $table->text('initiation_date')->nullable();
            $table->longText('short_description')->nullable();
            $table->integer('assign_to')->nullable();
            $table->text('due_date')->nullable();

            $table->text('scar_name')->nullable();   
            $table->text('owner_name')->nullable();   
            $table->text('followup_date')->nullable();   
            $table->longText('description')->nullable();  
            $table->text('supplier_site')->nullable();
            $table->longText('recommended_action')->nullable();   
            $table->text('supplier_site_contact_email')->nullable();   
            $table->text('supplier_product')->nullable();
            $table->longText('root_cause')->nullable();   
            $table->text('expected_closure_date')->nullable();
            $table->text('expected_closure_time')->nullable();   
            $table->longText('risk_analysis')->nullable();
            $table->longText('effectiveness_check_summary')->nullable();   
            $table->text('non_conformance')->nullable();   
            $table->longText('capa_plan')->nullable();   


            $table->integer('stage')->nullable();
            $table->text('status')->nullable();

            $table->text('submitted_by')->nullable();
            $table->text('submitted_on')->nullable();
            $table->longText('submitted_comment')->nullable();

            $table->text('acknowledge_by')->nullable();
            $table->text('acknowledge_on')->nullable();
            $table->longText('acknowledge_comment')->nullable();

            $table->text('workin_progress_by')->nullable();
            $table->text('workin_progress_on')->nullable();
            $table->longText('workin_progress_comment')->nullable();

            $table->text('response_submitted_by')->nullable();
            $table->text('response_submitted_on')->nullable();
            $table->longText('response_submitted_comment')->nullable();

            $table->text('rejected_by')->nullable();
            $table->text('rejected_on')->nullable();
            $table->longText('rejected_comment')->nullable();

            $table->text('approved_by')->nullable();
            $table->text('approved_on')->nullable();
            $table->longText('approved_comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scars');
    }
};
