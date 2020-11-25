<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeagueDecorationItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('league_decoration_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('decoration_id')->default(0);
            $table->string('title', 125)->nullable();
            $table->string('slug', 125)->nullable();
            // item_seq
            $table->timestamps();

            $table->index(['decoration_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('league_decoration_items');
    }
}
