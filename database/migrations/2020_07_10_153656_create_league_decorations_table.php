<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeagueDecorationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('league_decorations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('widget_title', 125)->nullable();
            $table->unsignedInteger('league_id')->default(0);
            $table->string('page_url', 100)->nullable();
            // dec_seq
            $table->timestamps();

            $table->index(['league_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('league_decorations');
    }
}
