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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->integer('record')->nullable();
            $table->integer('parent_id')->nullable();
            $table->text('parent_type')->nullable();
            $table->integer('initiator_id')->nullable();
            $table->text('date_opened')->nullable();
            $table->longText('short_description')->nullable();
            $table->integer('assign_to')->nullable();
            $table->text('due_date')->nullable();            
            $table->text('supplier_person')->nullable();
            $table->longText('logo_attachment')->nullable();
            $table->text('supplier_contact_person')->nullable();
            $table->text('supplier_products')->nullable();
            $table->longText('description')->nullable();
            $table->text('supplier_type')->nullable();
            $table->text('supplier_sub_type')->nullable();
            $table->text('supplier_other_type')->nullable();
            $table->text('supply_from')->nullable();
            $table->text('supply_to')->nullable();
            $table->text('supplier_website')->nullable();
            $table->text('supplier_web_search')->nullable();
            $table->longText('supplier_attachment')->nullable();
            $table->longText('related_url')->nullable();
            $table->longText('related_quality_events')->nullable();
            $table->text('intiation_date')->nullable();

            $table->text('supplier_name')->nullable();  
            $table->longText('other_contacts')->nullable();
            $table->text('contact_person')->nullable();
            $table->longText('supplier_serivce')->nullable();
            $table->text('zone')->nullable();
            $table->text('country')->nullable();
            $table->text('state')->nullable();
            $table->text('city')->nullable();
            $table->longText('address')->nullable();
            $table->longText('suppplier_web_site')->nullable();
            $table->text('iso_certified_date')->nullable();
            $table->text('suppplier_contacts')->nullable();
            $table->text('related_non_conformance')->nullable();
            $table->text('suppplier_agreement')->nullable();
            $table->text('regulatory_history')->nullable();
            $table->text('distribution_sites')->nullable();
            $table->longText('manufacturing_sited')->nullable();
            $table->longText('quality_management')->nullable();
            $table->longText('bussiness_history')->nullable();
            $table->longText('performance_history')->nullable();
            $table->longText('compliance_risk')->nullable();

            $table->text('cost_reduction')->nullable();          
            $table->text('cost_reduction_weight')->nullable();
            $table->text('payment_term')->nullable(); 
            $table->text('payment_term_weight')->nullable();          
            $table->text('lead_time_days')->nullable();
            $table->text('lead_time_days_weight')->nullable(); 
            $table->text('ontime_delivery')->nullable();          
            $table->text('ontime_delivery_weight')->nullable();
            $table->text('supplier_bussiness_planning')->nullable(); 
            $table->text('supplier_bussiness_planning_weight')->nullable();          
            $table->text('quality_system')->nullable();
            $table->text('quality_system_ranking')->nullable();
            $table->text('car_generated')->nullable();
            $table->text('car_generated_weight')->nullable();
            $table->text('closure_time')->nullable();
            $table->text('closure_time_weight')->nullable();
            $table->text('end_user_satisfaction')->nullable();
            $table->text('end_user_satisfaction_weight')->nullable();            
            $table->text('total_score')->nullable();
            $table->text('total_available_score')->nullable();
            $table->text('achieved_score')->nullable();

            $table->text('last_audit_date')->nullable();
            $table->text('next_audit_date')->nullable();
            $table->text('audit_frequency')->nullable();
            $table->text('last_audit_result')->nullable();
            $table->text('facility_type')->nullable();
            $table->text('nature_of_employee')->nullable();
            $table->text('technical_support')->nullable();
            $table->text('survice_supported')->nullable();
            $table->text('reliability')->nullable();
            $table->text('revenue')->nullable();
            $table->text('client_base')->nullable();
            $table->text('previous_audit_result')->nullable();
            $table->text('total_achieved_score')->nullable();

            $table->text('risk_raw_total')->nullable();
            $table->text('risk_assessment_total')->nullable();

            $table->text('submitted_by')->nullable();
            $table->text('submitted_on')->nullable();
            $table->longText('submitted_comment')->nullable();
            $table->text('cancelled_by')->nullable();
            $table->text('cancelled_on')->nullable();
            $table->longText('cancelled_comment')->nullable();

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
        Schema::dropIfExists('suppliers');
    }
};
