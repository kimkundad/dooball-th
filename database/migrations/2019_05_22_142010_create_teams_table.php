<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('api_id')->default(0);
            $table->string('team_name_th', 200); // in matches
            $table->string('team_name_en', 200); // in api-football.com
            $table->string('short_name_th', 200)->nullable();
            $table->string('long_name_th', 200)->nullable();
            $table->string('search_dooball', 200)->nullable();
            $table->string('search_graph', 200)->nullable();
            $table->string('league_url', 100)->nullable();
            $table->unsignedInteger('onpage_id')->default(0);
            $table->unsignedBigInteger('media_id')->default(0);
            $table->unsignedTinyInteger('active_status')->default(1);
            // $table->timestamps();

            $table->index(['media_id']);
            $table->index(['onpage_id']);
            $table->index(['api_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teams');
    }
}
