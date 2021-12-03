<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacanciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique('slug');
            $table->dateTime('active_from')->nullable();
            $table->dateTime('active_to')->nullable();
            $table->boolean('active')->nullable();
            $table->string('city')->nullable();
            $table->string('schedule')->nullable();
            $table->float('wage')->nullable();
            $table->string('link')->nullable();
            $table->string('preview_picture')->nullable();
            $table->text('preview_text')->nullable();
            $table->longText('detail_text')->nullable();
            $table->json('related')->nullable();
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
        Schema::dropIfExists('vacancies');
    }
}
