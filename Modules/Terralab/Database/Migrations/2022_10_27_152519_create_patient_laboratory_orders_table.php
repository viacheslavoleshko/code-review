<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientLaboratoryOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_laboratory_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('patient_id')->constrained('users')->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->foreignId('laboratory_id')->constrained('catalog_laboratories')->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->unsignedInteger('menstrphase')->nullable();
            $table->text('descr')->nullable();
            $table->string('order_number')->nullable();
            $table->string('order_status')->nullable();
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
        Schema::dropIfExists('patient_laboratory_orders');
    }
}
