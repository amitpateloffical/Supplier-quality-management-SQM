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
        Schema::create('medical_device_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('division_id')->nullable();
            $table->string('record_number')->nullable();
            $table->string('initiator_id')->nullable();
            $table->date('date_of_initiation')->nullable();
            $table->string('short_description')->nullable();
            $table->string('assign_to')->nullable();
            $table->date('due_date_gi')->nullable();
            $table->string('registration_type_gi')->nullable();
            $table->longText('file_attachment_gi')->nullable();
            $table->string('parent_record_number')->nullable();
            $table->string('local_record_number')->nullable();
            $table->string('zone_departments')->nullable();
            $table->string('country_number')->nullable();
            $table->string('regulatory_departments')->nullable();
            $table->string('registration_number')->nullable();
            $table->integer('risk_based_departments')->nullable();
            $table->integer('device_approval_departments')->nullable();
            $table->integer('marketing_auth_number')->nullable();
            $table->string('manufacturer_number')->nullable();
            $table->string('stage')->nullable();
            $table->string('status')->nullable();
            $table->string('audit_agenda_grid')->nullable();
            $table->string('manufacturing_description')->nullable();
            $table->string('dossier_number')->nullable();
            $table->string('dossier_departments')->nullable();
            $table->string('description')->nullable();    
            $table->date('planned_submission_date')->nullable();
            $table->date('actual_submission_date')->nullable();
            $table->date('actual_approval_date')->nullable();
            $table->date('actual_rejection_date')->nullable();
            $table->string('renewal_departments')->nullable();
            $table->date('next_renewal_date')->nullable();
            
            

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
        Schema::dropIfExists('medical_device_registrations');
    }
};
