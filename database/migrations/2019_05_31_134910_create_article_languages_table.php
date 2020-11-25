<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_languages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('article_id')->default(0);
            $table->char('language', 5)->nullable();
            $table->string('title', 200)->nullable();
            $table->text('description')->nullable();
            $table->text('detail')->nullable();
            $table->string('seo_title', 200)->nullable();
            $table->string('seo_description', 255)->nullable();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->timestamps();

            $table->index(['article_id']);
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
        Schema::dropIfExists('article_languages');
    }
}
