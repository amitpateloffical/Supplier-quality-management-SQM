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
        Schema::table('supplier_audits', function (Blueprint $table) {
            $table->text('supplier_audits')->nullable();
            $table->text('audit_preparation_comment')->nullable();
            $table->text('pending_response_comment')->nullable();
            $table->text('capa_execution_in_progress_comment')->nullable();
            $table->text('comment_closed_done_by_comment')->nullable();
            $table->text('comment_rejected_comment')->nullable();
            $table->text('comment_cancelled_comment')->nullable();   
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplier_audits', function (Blueprint $table) {
            //
        });
    }
};
