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
        Schema::create('external_audit_grid_suppliers', function (Blueprint $table) {

            $table->id();
            $table->integer('audit_id')->nullable();
            $table->text('type')->nullable();
            $table->text('severity_level')->nullable();
            $table->text('observation_id')->nullable();
            $table->text('observation_description')->nullable();
            $table->text('area')->nullable();
            $table->text('auditee_response')->nullable();
            $table->text('area_of_audit')->nullable();
            $table->text('start_date')->nullable();
            $table->text('start_time')->nullable();
            $table->text('end_date')->nullable();
            $table->text('end_time')->nullable();
            $table->text('auditor')->nullable();
            $table->text('auditee')->nullable();
            $table->text('remark')->nullable();

           	
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
        Schema::dropIfExists('external_audit_grid_suppliers');
    }
};
