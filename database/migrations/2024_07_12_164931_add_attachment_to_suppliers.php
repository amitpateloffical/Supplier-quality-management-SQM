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
            $table->longText('iso_certificate_attachment')->nullable();
            $table->longText('gi_additional_attachment')->nullable();
            $table->longText('hod_additional_attachment')->nullable();
            $table->longText('supplier_detail_additional_attachment')->nullable();
            $table->longText('score_card_additional_attachment')->nullable();
            $table->longText('qa_reviewer_additional_attachment')->nullable();
            $table->longText('risk_assessment_additional_attachment')->nullable();
            $table->longText('qa_head_additional_attachment')->nullable();
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
