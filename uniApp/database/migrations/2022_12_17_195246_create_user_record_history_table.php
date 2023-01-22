<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRecordHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_record_history', function (Blueprint $table) {
            $table->id();
            $table->string('openid')->comment('微信唯一标识');
            $table->string('plan_book')->comment('计划词库');
            $table->integer('plan_days')->comment('计划天数');
            $table->integer('plan_day_words')->comment('计划每日单词数');
            $table->integer('finish_word')->comment('完成单词数目');
            $table->json('finish_list')->comment('完成单词列表');
            $table->integer('finish_day')->comment('完成天数');
            $table->json('random_list')->comment('随机词表');
            $table->json('like_list')->comment('收藏单词表');
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
        Schema::dropIfExists('user_record_history');
    }
}
