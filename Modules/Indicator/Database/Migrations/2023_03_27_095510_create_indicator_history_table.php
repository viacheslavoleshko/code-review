<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndicatorHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicator_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('history_id');
            $table->string('original_indicator_name')->nullable();
            $table->unsignedBigInteger('indicator_id')->nullable();
            $table->text('original_norm_text')->nullable();
            $table->boolean('norm_flag')->nullable();
            $table->string('result');
            $table->string('original_measure_type')->nullable();
            $table->unsignedBigInteger('measure_type_id')->nullable();
            $table->dateTime('validated_at')->nullable();
            $table->unsignedBigInteger('validated_who')->nullable();
            $table->json('probably_measure_types')->nullable();
            $table->json('probably_indicators')->nullable();
            $table->timestamps();

            $table->foreign('history_id')->references('id')->on('analysis_history')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('indicator_id')->references('id')->on('catalog_indicators')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('measure_type_id')->references('id')->on('catalog_measure_types')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('validated_who')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unique(['history_id', 'indicator_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('indicator_history', function (Blueprint $table) {
            $table->dropForeign('history_id');
            $table->dropForeign('indicator_id');
            $table->dropForeign('measure_type_id');
            $table->dropForeign('validated_who');
            $table->dropUnique(['history_id', 'indicator_id']);
        });
        Schema::dropIfExists('indicator_history');
    }
}
