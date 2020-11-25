<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWidgetOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widget_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('widget_dom_id', 125)->nullable();
            $table->string('position_dom_id', 125)->nullable();
            $table->unsignedTinyInteger('language_id')->default(1);
            $table->string('title', 125)->nullable();
            $table->text('detail')->nullable();
            $table->unsignedTinyInteger('show_title_name')->default(0);
            $table->unsignedTinyInteger('active_status')->default(1);
            $table->timestamps();

            $table->index(['widget_dom_id']);
            $table->index(['position_dom_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widget_orders');
    }
}
