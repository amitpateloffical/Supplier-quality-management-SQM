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
            $table->text('initiation_group')->nullable();
            $table->text('initiator_group_code')->nullable();
            $table->text('manufacturerName')->nullable();
            $table->text('starting_material')->nullable();
            $table->text('material_code')->nullable();
            $table->text('pharmacopoeial_claim')->nullable();
            $table->text('cep_grade')->nullable();
            $table->longText('cep_attachment')->nullable();
            $table->text('request_for')->nullable();
            $table->text('attach_batch')->nullable();
            $table->longText('request_justification')->nullable();
            $table->text('manufacturer_availability')->nullable();
            $table->text('request_accepted')->nullable();
            $table->longText('cqa_remark')->nullable();
            $table->text('accepted_by')->nullable();
            $table->text('accepted_on')->nullable();
            $table->text('pre_purchase_sample')->nullable();
            $table->longText('justification')->nullable();
            $table->text('pre_purchase_sample_analysis')->nullable();
            $table->text('availability_od_coa')->nullable();
            $table->text('analyzed_location')->nullable();
            $table->longText('cqa_comment')->nullable();
            $table->text('materialName')->nullable();
            $table->text('manufacturerNameNew')->nullable();
            $table->text('analyzedLocation')->nullable();
            $table->longText('cqa_corporate_comment')->nullable();
            $table->longText('coa_attachment')->nullable();
            $table->text('sample_ordered')->nullable();
            $table->text('sample_order_justification')->nullable();
            $table->longText('trail_status_feedback')->nullable();
            $table->text('sample_stand_approved')->nullable();

            $table->text('tse_bse')->nullable();
            $table->longText('tse_bse_remark')->nullable();
            
            $table->text('residual_solvent')->nullable();
            $table->longText('residual_solvent_remark')->nullable();
            
            $table->text('gmo')->nullable();
            $table->longText('gmo_remark')->nullable();
            
            $table->text('melamine')->nullable();
            $table->longText('melamine_remark')->nullable();
            
            $table->text('gluten')->nullable();
            $table->longText('gluten_remark')->nullable();
            
            $table->text('nitrosamine')->nullable();
            $table->longText('nitrosamine_remark')->nullable();
            
            $table->text('who')->nullable();
            $table->longText('who_remark')->nullable();
            
            $table->text('gmp')->nullable();
            $table->longText('gmp_remark')->nullable();
            
            $table->text('iso_certificate')->nullable();
            $table->longText('iso_certificate_remark')->nullable();
            
            $table->text('manufacturing_license')->nullable();
            $table->longText('manufacturing_license_remark')->nullable();
            
            $table->text('cep')->nullable();
            $table->longText('cep_remark')->nullable();
            
            $table->text('msds')->nullable();
            $table->longText('msds_remark')->nullable();
            
            $table->text('elemental_impurities')->nullable();
            $table->longText('elemental_impurities_remark')->nullable();
            
            $table->text('declaration')->nullable();
            $table->longText('declaration_remark')->nullable();

            $table->text('supply_chain_availability')->nullable();
            $table->text('quality_agreement_availability')->nullable();
            $table->text('risk_assessment_done')->nullable();
            $table->text('risk_rating')->nullable();
            $table->text('manufacturer_audit_planned')->nullable();
            $table->text('manufacturer_audit_conducted')->nullable();
            $table->text('manufacturer_can_be')->nullable();

            $table->text('cqa_coordinator')->nullable();
            $table->text('cqa_designee')->nullable();
            $table->text('acknowledge_by')->nullable();
            
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
