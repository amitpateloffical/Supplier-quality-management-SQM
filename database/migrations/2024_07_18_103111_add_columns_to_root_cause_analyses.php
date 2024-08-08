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
        Schema::table('root_cause_analyses', function (Blueprint $table) {
            $table->longText('acknowledge_comment')->nullable();
            $table->longText('submitted_comment')->nullable();
            $table->text('moreinfo_by')->nullable();
            $table->text('moreinfo_on')->nullable();
            $table->longText('moreinfo_comment')->nullable();
            $table->longText('qA_review_complete_comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('root_cause_analyses', function (Blueprint $table) {
            //
        });
    }
};
