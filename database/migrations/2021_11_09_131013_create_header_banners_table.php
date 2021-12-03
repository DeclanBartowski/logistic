<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeaderBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('header_banners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('link')->unique('link');
            $table->string('url')->nullable();
            $table->string('description')->nullable();
            $table->string('mob_picture')->nullable();
            $table->string('picture')->nullable();
            $table->text('text')->nullable();
            $table->string('wages_title')->nullable();
            $table->json('wages')->nullable();
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
        Schema::dropIfExists('header_banners');
    }
}
