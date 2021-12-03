<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique('slug');
            $table->string('tag')->nullable();
            $table->dateTime('active_from')->nullable();
            $table->dateTime('active_to')->nullable();
            $table->boolean('active')->nullable();
            $table->string('link')->nullable();
            $table->string('preview_picture')->nullable();
            $table->text('preview_text')->nullable();
            $table->longText('detail_text')->nullable();
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
        Schema::dropIfExists('publications');
    }
}
