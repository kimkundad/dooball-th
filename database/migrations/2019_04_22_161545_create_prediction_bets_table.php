<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prediction_bets', function (Blueprint $table) {
            $table->increments('id');
            // $table->string('detail_id', 125)->nullable();
            $table->string('detail_id', 125)->unique();
            $table->unsignedInteger('match_result_status')->default(0);
            $table->timestamps();
            
            $table->index(['detail_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prediction_bets');
    }
}
