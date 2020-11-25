<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('generals', function (Blueprint $table) {
            $table->increments('id');
            // $table->string('general_key', 125);
            // $table->string('general_value', 125);
            $table->string('website_name', 255)->nullable();
            $table->unsignedBigInteger('media_id')->default(0);
            $table->string('website_description', 255)->nullable();
            $table->unsignedTinyInteger('website_robot')->default(0);
            $table->string('website_gg_ua', 255)->nullable();
            $table->string('social_facebook', 125)->nullable();
            $table->string('social_twitter', 125)->nullable();
            $table->string('social_linkedin', 125)->nullable();
            $table->string('social_youtube', 125)->nullable();
            $table->string('social_instagram', 125)->nullable();
            $table->string('social_pinterest', 125)->nullable();

            $table->index(['media_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('generals');
    }
}
