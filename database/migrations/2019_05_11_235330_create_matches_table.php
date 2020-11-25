<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('match_name', 200);
            $table->timestamp('match_time')->nullable();
            $table->string('home_team', 125)->nullable();
            $table->string('away_team', 125)->nullable();
            $table->string('match_result', 125)->nullable();
            $table->unsignedDecimal('bargain_price', 10, 2)->default(0.00);
            $table->string('channels', 200)->nullable();
            $table->text('more_detail')->nullable();
            $table->unsignedSmallInteger('match_seq')->default(999);
            $table->unsignedTinyInteger('active_status')->default(1);
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
        Schema::dropIfExists('matches');
    }
}
