<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeagueSubpagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('league_subpages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('page_url', 125);
            $table->string('league_url', 100)->nullable();
            $table->unsignedInteger('onpage_id')->default(0);
            $table->timestamps();

            $table->index(['onpage_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('league_subpages');
    }
}
