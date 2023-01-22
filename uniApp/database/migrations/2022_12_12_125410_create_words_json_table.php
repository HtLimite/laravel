<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWordsJsonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('words_json', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('书名');
            $table->string('pic')->comment('封面');
            $table->string('details')->comment('介绍');
            $table->integer('person')->comment('背诵人数');
            $table->integer('count')->comment('总数');
//            $table->json('word_json')->comment('词库json');
//            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('words_json');
    }
}
