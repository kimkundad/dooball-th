<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_links', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('match_id')->default(0);
            $table->enum('link_type', ['Normal', 'Sponsor'])->default('Normal');
            $table->string('name', 200)->nullable();
            $table->string('url', 200)->nullable();
            $table->string('desc', 200)->nullable();
            $table->unsignedSmallInteger('link_seq')->default(1);
            $table->unsignedTinyInteger('link_star')->default(1);

            $table->index(['match_id']);
            // $table->foreign('match_id')->references('id')->on('matches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_links');
    }
}
