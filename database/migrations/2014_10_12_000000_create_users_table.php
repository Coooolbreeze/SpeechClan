<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nickname');
            $table->string('avatar');
            $table->tinyInteger('sex')->default(0);
            $table->string('account')->unique()->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->tinyInteger('is_bind_account')->default(0);
            $table->tinyInteger('is_bind_phone')->default(0);
            $table->tinyInteger('is_bind_email')->default(0);
            $table->tinyInteger('is_bind_wxopen')->default(0);
            $table->tinyInteger('is_bind_wxmedia')->default(0);
            $table->string('guard')->default('ordinary');
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
        Schema::dropIfExists('users');
    }
}
