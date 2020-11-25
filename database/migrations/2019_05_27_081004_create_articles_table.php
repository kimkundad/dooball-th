<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('code', 13)->nullable();
            $table->unsignedInteger('category_id')->default(0);
            $table->string('title', 200)->nullable();
            $table->string('slug', 200)->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('team_id')->default(0);
            $table->unsignedBigInteger('tournament_id')->default(0);
            $table->unsignedBigInteger('channel_id')->default(0);
            $table->text('detail')->nullable();
            $table->string('seo_title', 200)->nullable();
            $table->string('seo_description', 255)->nullable();
            $table->unsignedBigInteger('media_id')->default(0);
            $table->unsignedBigInteger('count_view')->default(0);
            $table->unsignedBigInteger('count_like')->default(0);
            $table->unsignedBigInteger('user_id')->default(0);
            $table->string('related', 255)->nullable();
            $table->unsignedTinyInteger('article_status')->default(0);
            $table->unsignedTinyInteger('active_status')->default(1);
            $table->timestamps();

            // $table->foreign('category_id')->references('id')->on('categories');
            $table->index(['category_id']);
            $table->index(['team_id']);
            $table->index(['tournament_id']);
            $table->index(['channel_id']);
            $table->index(['media_id']);
            $table->index(['user_id']);
            // $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
