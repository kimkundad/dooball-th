<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('on_pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            // code_name string 50
            // seo_title string 200
            // seo_description string 200
            // page_top int 10
            // page_bottom int 10
            // active_status tinyint 1 => default 1
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('on_pages');
    }
}
