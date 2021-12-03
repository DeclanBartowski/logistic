<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWagePropertyValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wage_property_values', function (Blueprint $table) {
            $table->id();
            $table->string('value')->nullable();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('wage_property_id')->nullable();
            $table->foreign('wage_property_id')->references('id')->on('wage_properties')->nullOnDelete();
            $table->unsignedBigInteger('wage_property_option_id')->nullable();
            $table->foreign('wage_property_option_id')->references('id')->on('wage_property_options')->nullOnDelete();
            $table->unsignedBigInteger('wage_id');
            $table->foreign('wage_id')->references('id')->on('wages')->onDelete('cascade');
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
        Schema::dropIfExists('wage_property_values');
    }
}
