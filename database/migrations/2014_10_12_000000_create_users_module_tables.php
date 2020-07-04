<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersModuleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->index(['name']);
        });

        Schema::create('user_settings', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_id');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->string('nickname')->nullable();
            $table->string('timezone')->default('Europe/Kiev');
            $table->string('phone')->nullable();

            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('country')->nullable();

            $table->string('lang')->default('en');

            $table->string('avatar_path', 1500)->nullable();

            $table->timestamps();

            $table->index(['user_id']);
            $table->index(['nickname']);
        });

        Schema::create('user_contacts', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->unsignedBigInteger('contact_user_id');
            $table->foreign('contact_user_id')
                ->references('id')
                ->on('users');

            $table->string('alias', 300)->nullable();

            $table->unique(['user_id', 'contact_user_id']);

        });

        Schema::create('user_blocked_contacts', function(Blueprint $table) {

            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->unsignedBigInteger('blocked_user_id');
            $table->foreign('blocked_user_id')
                ->references('id')
                ->on('users');

            $table->unique(['user_id', 'blocked_user_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_blocked_contacts');
        Schema::dropIfExists('user_contacts');
        Schema::dropIfExists('user_settings');
        Schema::dropIfExists('users');
    }
}
