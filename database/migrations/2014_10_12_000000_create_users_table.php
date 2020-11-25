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
            $table->bigIncrements('id');
            $table->enum('role', ['Member', 'Admin'])->default('Member');
            $table->string('username', 50)->nullable();
            $table->string('email', 125)->nullable();
            $table->string('first_name', 125)->nullable();
            $table->string('last_name', 125)->nullable();
            $table->string('screen_name', 200)->nullable();
            $table->string('avatar', 200)->nullable();
            $table->string('line_id', 50)->nullable();
            $table->string('tel', 125)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('provider', 50)->nullable();
            $table->string('provider_id', 50)->nullable();
            $table->text('access_token')->nullable();
            $table->unsignedTinyInteger('user_status')->default(1);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
