<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->after('staff_name', function ($table) {
                $table->string('email');
                $table->string('phone');
                $table->string('appointment');
                $table->string('department');
                $table->string('address', 500);
            });
            $table->after('vehicle_type', function ($table) {
                $table->string('vehicle_model');
                $table->string('vehicle_color');
            });
            $table->after('vehicle_plate_no', function ($table) {
                $table->string('vehicle_chasis_no');
                $table->string('authorized_staff_name');
                $table->string('authorized_staff_appointment');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tags', function (Blueprint $table) {
            //
        });
    }
}
