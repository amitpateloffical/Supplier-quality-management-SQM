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
