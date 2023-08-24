<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalysisHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analysis_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');          
            $table->unsignedBigInteger('laboratory_id');          
            $table->dateTime('date');
            $table->string('original_analys_name')->nullable();
            $table->unsignedBigInteger('analys_id')->nullable();           
            $table->string('src_type');
            $table->json('probably_analysis')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('laboratory_id')->references('id')->on('catalog_laboratories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('analys_id')->references('id')->on('catalog_analysis')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('analysis_history', function (Blueprint $table) {
            $table->dropForeign('user_id');
            $table->dropForeign('laboratory_id');
            $table->dropForeign('analys_id');
        });
        Schema::dropIfExists('analysis_history');
    }
}
