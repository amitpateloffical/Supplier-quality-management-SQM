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
            $table->text('manufacturer_name')->after('supplier_name')->nullable();
            $table->text('vendor_name')->after('manufacturer_name')->nullable();

            $table->text('rejection_ppm')->after('quality_system')->nullable();
            $table->text('rejection_ppm_weight')->after('rejection_ppm')->nullable();

            $table->text('risk_average')->nullable();
            $table->text('risk_median')->nullable();
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
