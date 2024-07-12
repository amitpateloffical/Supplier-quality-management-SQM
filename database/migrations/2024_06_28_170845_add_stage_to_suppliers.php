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
            $table->string('scorecard_record')->nullable()->after('end_user_satisfaction_weight');
            $table->text('type')->nullable()->after('id'); 
            $table->text('supplier_id')->nullable()->after('supplier_person');
            $table->text('manufacturer_id')->nullable()->after('supplier_id');
            $table->text('vendor_id')->nullable()->after('manufacturer_id');

            $table->integer('division_id')->nullable()->after('type');
            $table->integer('stage')->nullable()->after('cancelled_comment');
            $table->text('status')->nullable()->after('stage');
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
