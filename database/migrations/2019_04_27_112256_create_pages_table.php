<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('page_name', 200)->nullable();
            $table->string('slug', 200)->nullable();
            $table->string('title', 200)->nullable();
            $table->text('description')->nullable();
            $table->text('detail')->nullable();
            $table->enum('page_condition', ['L', 'T'])->default('L');
            $table->string('league_name', 125)->nullable();
            $table->string('team_name', 125)->nullable();
            $table->string('seo_title', 200)->nullable();
            $table->string('seo_description', 255)->nullable();
            $table->unsignedBigInteger('count_view')->default(0);
            $table->unsignedBigInteger('count_like')->default(0);
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedTinyInteger('show_on_menu')->default(0);
            $table->unsignedTinyInteger('active_status')->default(1);
            $table->timestamps();

            $table->index(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
