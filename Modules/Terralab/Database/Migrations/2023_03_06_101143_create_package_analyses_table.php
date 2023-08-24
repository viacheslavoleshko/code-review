<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageAnalysesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_analyses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('catalog_service_package_id')->references('id')->on('catalog_service_packages')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->json('analysis');
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
