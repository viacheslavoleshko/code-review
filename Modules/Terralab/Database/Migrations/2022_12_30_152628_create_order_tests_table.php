<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_tests', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignUuid('order_laboratory_id')->constrained('patient_laboratory_orders')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->unsignedBigInteger('indicator_id');
            $table->string('name');
            $table->string('material');
            $table->boolean('ready')->default(false);
            $table->boolean('pdf')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_tests');
    }
}
