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
        Schema::create('supplier_sites', function (Blueprint $table) {
            $table->id();
            $table->integer('record')->nullable();
            $table->integer('parent_id')->nullable();
            $table->text('parent_type')->nullable();
            $table->text('type')->nullable();
            $table->integer('division_id')->nullable();
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
            $table->text('manufacturer_name')->nullable();
            $table->text('vendor_name')->nullable();
            $table->text('supplier_id')->nullable();
            $table->text('manufacturer_id')->nullable();
            $table->text('vendor_id')->nullable();
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

            $table->text('rejection_ppm')->nullable();
            $table->text('rejection_ppm_weight')->nullable();

            $table->text('risk_average')->nullable();
            $table->text('risk_median')->nullable();

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
            $table->string('scorecard_record')->nullable();            
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

            $table->integer('stage')->nullable();
            $table->text('status')->nullable();

            $table->text('submitted_by')->nullable();
            $table->text('submitted_on')->nullable();
            $table->longText('submitted_comment')->nullable();
            $table->text('cancelled_by')->nullable();
            $table->text('cancelled_on')->nullable();
            $table->longText('cancelled_comment')->nullable();

            $table->longText('HOD_feedback')->nullable();
            $table->longText('HOD_comment')->nullable();
            $table->longText('HOD_attachment')->nullable();

            $table->longText('QA_reviewer_feedback')->nullable();
            $table->longText('QA_reviewer_comment')->nullable();
            $table->longText('QA_reviewer_attachment')->nullable();

            $table->longText('QA_head_comment')->nullable();
            $table->longText('QA_head_attachment')->nullable();

            $table->text('pending_qualification_by')->nullable();
            $table->text('pending_qualification_on')->nullable();
            $table->longText('pending_qualification_comment')->nullable();

            $table->text('pending_supplier_audit_by')->nullable();
            $table->text('pending_supplier_audit_on')->nullable();
            $table->longText('pending_supplier_audit_comment')->nullable();

            $table->text('pending_rejection_by')->nullable();
            $table->text('pending_rejection_on')->nullable();
            $table->longText('pending_rejection_comment')->nullable();

            $table->text('supplier_approved_by')->nullable();
            $table->text('supplier_approved_on')->nullable();
            $table->longText('supplier_approved_comment')->nullable();

            $table->text('supplier_approved_to_obselete_by')->nullable();
            $table->text('supplier_approved_to_obselete_on')->nullable();
            $table->longText('supplier_approved_to_obselete_comment')->nullable();
            
            $table->text('reAudit_by')->nullable();
            $table->text('reAudit_on')->nullable();
            $table->longText('reAudit_comment')->nullable();

            $table->text('rejectedDueToQuality_by')->nullable();
            $table->text('rejectedDueToQuality_on')->nullable();
            $table->longText('rejectedDueToQuality_comment')->nullable();

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
        Schema::dropIfExists('supplier_sites');
    }
};
