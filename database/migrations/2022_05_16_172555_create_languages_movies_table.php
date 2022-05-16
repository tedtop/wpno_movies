<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagesMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages_movies', function (Blueprint $table) {
            // $table->id();
            $table->foreignId('movie_id')->constrained();
            $table->foreignId('language_id')->constrained();
            // $table->timestamps();
            // $table->unique('movie_id', 'language_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies_languages');
    }
}
