<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('link');
            $table->integer('sort')->default(500);
            $table->unsignedBigInteger('menu_type_id');
            $table->foreign('menu_type_id')->references('id')->on('menu_types')->onDelete('cascade');
            $table->unsignedBigInteger('menu_group_id')->nullable();
            $table->foreign('menu_group_id')->references('id')->on('menu_groups')->onDelete('cascade');
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
        Schema::dropIfExists('menu_items');
    }
}
