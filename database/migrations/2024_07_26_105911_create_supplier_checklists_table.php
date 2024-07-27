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
        Schema::create('supplier_checklists', function (Blueprint $table) {
            $table->id();
            $table->integer('supplier_id')->nullable();

            $table->longText('tse_attachment')->nullable();
            $table->text('certificate_issue_tse')->nullable();
            $table->text('certificate_expiry_tse')->nullable();
            $table->longText('tse_bse_remark')->nullable();

            $table->longText('residual_solvent_attachment')->nullable();
            $table->text('certificate_issue_residual_solvent')->nullable();
            $table->text('certificate_expiry_residual_solvent')->nullable();
            $table->longText('residual_solvent_remark')->nullable();

            $table->longText('melamine_attachment')->nullable();
            $table->text('certificate_issue_melamine')->nullable();
            $table->text('certificate_expiry_melamine')->nullable();
            $table->longText('melamine_remark')->nullable();

            $table->longText('gmo_attachment')->nullable();
            $table->text('certificate_issue_gmo')->nullable();
            $table->text('certificate_expiry_gmo')->nullable();
            $table->longText('gmo_remark')->nullable();

            $table->longText('gluten_attachment')->nullable();
            $table->text('certificate_issue_gluten')->nullable();
            $table->text('certificate_expiry_gluten')->nullable();
            $table->longText('gluten_remark')->nullable();

            $table->longText('manufacturer_evaluation_attachment')->nullable();
            $table->text('certificate_issue_manufacturer_evaluation')->nullable();
            $table->text('certificate_expiry_manufacturer_evaluation')->nullable();
            $table->longText('manufacturer_evaluation_remark')->nullable();

            $table->longText('who_attachment')->nullable();
            $table->text('certificate_issue_who')->nullable();
            $table->text('certificate_expiry_who')->nullable();
            $table->longText('who_remark')->nullable();

            $table->longText('gmp_attachment')->nullable();
            $table->text('certificate_issue_gmp')->nullable();
            $table->text('certificate_expiry_gmp')->nullable();
            $table->longText('gmp_remark')->nullable();

            $table->longText('ISO_attachment')->nullable();
            $table->text('certificate_issue_ISO')->nullable();
            $table->text('certificate_expiry_ISO')->nullable();
            $table->longText('ISO_remark')->nullable();

            $table->longText('manufacturing_licenseISO_attachment')->nullable();
            $table->text('certificate_issue_manufacturing_license')->nullable();
            $table->text('certificate_expiry_manufacturing_license')->nullable();
            $table->longText('manufacturing_license_remark')->nullable();

            $table->longText('CEP_attachment')->nullable();
            $table->text('certificate_issue_CEP')->nullable();
            $table->text('certificate_expiry_CEP')->nullable();
            $table->longText('CEP_remark')->nullable();

            $table->longText('risk_assessment_attachment')->nullable();
            $table->text('certificate_issue_risk_assessment')->nullable();
            $table->text('certificate_expiry_risk_assessment')->nullable();
            $table->longText('risk_assessment_remark')->nullable();

            $table->longText('elemental_impurity_attachment')->nullable();
            $table->text('certificate_issue_elemental_impurity')->nullable();
            $table->text('certificate_expiry_elemental_impurity')->nullable();
            $table->longText('elemental_impurity_remark')->nullable();

            $table->longText('azido_impurities_attachment')->nullable();
            $table->text('certificate_issue_azido_impurities')->nullable();
            $table->text('certificate_expiry_azido_impurities')->nullable();
            $table->longText('azido_impurities_remark')->nullable();
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
        Schema::dropIfExists('supplier_checklists');
    }
};
