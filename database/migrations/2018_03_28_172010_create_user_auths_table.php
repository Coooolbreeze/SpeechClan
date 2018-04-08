<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAuthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_auths', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('platform')->comment('登录平台');
            $table->string('identity_type')->comment('登录类型');
            $table->string('identifier')->comment('登录标识');
            $table->string('credential')->comment('登录凭证');
            $table->string('remark')->nullable()->comment('存储一些特定信息，如微信unionid');
            $table->tinyInteger('verified')->default(0)->comment('0未验证 1已验证');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_auths');
    }
}
