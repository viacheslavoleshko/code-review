<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTestResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_test_results', function (Blueprint $table) {
            $table->id();

            $table->string('order_test_id');
            $table->foreign('order_test_id')->references('id')->on('order_tests')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->string('material_name');
            $table->string('test_name');
            $table->string('test_code');
            $table->string('gis')->nullable();
            $table->string('indicator_name');
            $table->string('unit_name')->nullable();;
            $table->string('result');
            $table->text('norm_text');
            $table->string('ubnormal_flag')->nullable();
            $table->string('payload')->nullable();
            $table->string('order')->nullable();
            $table->string('date_ready')->nullable();
            $table->string('done_employee_name')->nullable();
            $table->string('done_employee_id')->nullable();
            $table->string('done_employee_post')->nullable();
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
        Schema::dropIfExists('order_test_results');
    }
}
