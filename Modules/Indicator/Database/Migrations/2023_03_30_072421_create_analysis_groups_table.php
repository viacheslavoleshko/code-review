<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalysisGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analysis_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('indicator_id');
            $table->unsignedBigInteger('analys_id');
            $table->timestamps();
            
            $table->unique(['indicator_id', 'analys_id']);
            $table->foreign('indicator_id')->references('id')->on('catalog_indicators')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::table('analysis_groups', function (Blueprint $table) {
            $table->dropForeign('indicator_id');
            $table->dropForeign('analys_id');
            $table->dropUnique(['indicator_id', 'analys_id']);
        });
        Schema::dropIfExists('analysis_groups');
    }
}
