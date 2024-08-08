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
        Schema::table('capas', function (Blueprint $table) {
            $table->text('pending_more_info_review_by')->nullable();
            $table->text('pending_more_info_review_on')->nullable();
            $table->longText('pending_more_info_review_comment')->nullable();
            $table->text('reject_more_info_review_by')->nullable();
            $table->text('reject_more_info_review_on')->nullable();
            $table->longText('reject_more_info_review_comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('capas', function (Blueprint $table) {
            //
        });
    }
};
