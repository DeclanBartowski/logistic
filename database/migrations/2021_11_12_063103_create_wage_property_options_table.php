<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWagePropertyOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wage_property_options', function (Blueprint $table) {
            $table->id();
            $table->string('value');
            $table->string('hint')->nullable();
            $table->integer('sort')->default(500);
            $table->unsignedBigInteger('wage_property_id');
            $table->foreign('wage_property_id')->references('id')->on('wage_properties')->onDelete('cascade');
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
        Schema::dropIfExists('wage_property_options');
    }
}
