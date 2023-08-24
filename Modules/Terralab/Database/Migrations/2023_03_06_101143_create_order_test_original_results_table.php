<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTestOriginalResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_test_original_results', function (Blueprint $table) {
            $table->id();

            $table->string('order_test_id');
            $table->foreign('order_test_id')->references('id')->on('order_tests')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->string('uri');
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
        Schema::dropIfExists('order_test_original_results');
    }
}
