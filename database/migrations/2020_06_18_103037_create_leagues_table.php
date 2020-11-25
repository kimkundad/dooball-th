<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leagues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('api_id')->default(0);
            $table->string('name_th', 200)->nullable();
            $table->string('name_en', 200)->nullable();
            $table->string('short_name', 125)->nullable();
            $table->string('long_name', 200)->nullable();
            $table->string('url', 200)->nullable();
            $table->text('years')->nullable();
            $table->unsignedInteger('onpage_id')->default(0);
            $table->unsignedBigInteger('media_id')->default(0);
            $table->unsignedTinyInteger('highlight')->default(0);
            $table->unsignedTinyInteger('active_status')->default(1);

            $table->index(['media_id']);
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
        Schema::dropIfExists('leagues');
    }
}
