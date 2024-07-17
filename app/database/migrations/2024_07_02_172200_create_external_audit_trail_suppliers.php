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
        Schema::create('external_audit_trail_suppliers', function (Blueprint $table) {

            $table->id();
            $table->string('audit_id')->nullable();
            $table->text('supplier_id')->nullable();
            $table->text('type')->nullable();
            $table->text('area_of_audit')->nullable();
            $table->text('start_date')->nullable();
            $table->text('end_date')->nullable();
            $table->text('start_time')->nullable();
            $table->text('end_time')->nullable();
            $table->text('auditor')->nullable();
            $table->text('auditee')->nullable();
            $table->text('remark')->nullable();
            $table->string('activity_type')->nullable();
            $table->longText('previous')->nullable();
            $table->string('stage')->nullable();
            $table->longText('current')->nullable();
            $table->longText('comment')->nullable();
            $table->text('action_name')->nullable();
            $table->text('change_from')->nullable();
            $table->text('change_to')->nullable();
            $table->string('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('origin_state')->nullable();
            $table->string('user_role')->nullable();
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
        Schema::dropIfExists('external_audit_trail_suppliers');
    }
};
