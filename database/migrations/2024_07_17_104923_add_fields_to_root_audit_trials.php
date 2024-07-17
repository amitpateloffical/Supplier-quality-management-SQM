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
        Schema::table('root_audit_trials', function (Blueprint $table) {
            $table->text('change_to')->nullable();
            $table->text('action_name')->nullable();
            $table->text('change_from')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('root_audit_trials', function (Blueprint $table) {
            //
        });
    }
};
