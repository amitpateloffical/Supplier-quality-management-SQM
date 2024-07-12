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
            $table->longText('HOD_feedback')->nullable();
            $table->longText('HOD_comment')->nullable();
            $table->longText('HOD_attachment')->nullable();

            $table->longText('QA_reviewer_feedback')->nullable();
            $table->longText('QA_reviewer_comment')->nullable();
            $table->longText('QA_reviewer_attachment')->nullable();

            $table->longText('QA_head_comment')->nullable();
            $table->longText('QA_head_attachment')->nullable();
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
