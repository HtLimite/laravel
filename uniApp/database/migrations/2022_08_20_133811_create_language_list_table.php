<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguageListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('language_list', function (Blueprint $table) {
            $table->id();
            $table->string('name', 10)->unique();
            $table->json('details')->nullable();
            $table->timestamps();

        });
        Schema::table('language_list', function (Blueprint $table) {
            $table->foreignId('language_id')
                ->constrained('language')
                ->cascadeOnUpdate();
        });

    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('language_list');

    }
}
