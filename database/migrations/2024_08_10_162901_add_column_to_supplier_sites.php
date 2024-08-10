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
        Schema::table('supplier_sites', function (Blueprint $table) {
            $table->text('conditionally_approved_by')->nullable();
            $table->text('conditionally_approved_on')->nullable();
            $table->longText('conditionally_approved_comments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplier_sites', function (Blueprint $table) {
            //
        });
    }
};