<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWxUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wx_users', function (Blueprint $table) {
            $table->id();
            $table->string('openid')->unique()->comment('用户小程序唯一id');
            $table->integer('limid')->unique()->comment('用户lim唯一id');
            $table->string('nickname')->comment('用户小程序昵称');
            $table->string('avatar')->comment('用户小程序头像');
            $table->string('country')->comment('用户小程序国家')->nullable();
            $table->string('province')->comment('用户小程序省份')->nullable();
            $table->string('city')->comment('用户小程序城市')->nullable();
            $table->string('weixin_session_key')->comment('用户登陆session');
            $table->string('gender')->comment('用户性别: 1 -> 女，0 -> 男');
            $table->string('name')->nullable()->comment('用户真实姓名')->nullable();
            $table->string('email')->nullable()->comment('用户邮箱')->nullable();
            $table->ipAddress('ip')->comment('ip地址');
            $table->string('location')->comment('地理位置');
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
        Schema::dropIfExists('wx_users');
    }
}
