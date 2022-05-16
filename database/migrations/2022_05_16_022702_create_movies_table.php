<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->index();
            $table->string('Title');
            $table->string('Year');
            $table->string('Rated');
            $table->string('Released');
            $table->string('Runtime');
            // $table->string('Genre');
            $table->string('Director');
            $table->string('Writer');
            $table->string('Actors');
            $table->text('Plot');
            // $table->string('Language');
            $table->string('Country');
            $table->string('Awards');
            $table->string('Poster');
            $table->text('Ratings');
            $table->string('Metascore');
            $table->string('imdbRating');
            $table->string('imdbVotes');
            $table->string('imdbID')->unique();
            $table->string('Type');
            $table->string('DVD');
            $table->string('BoxOffice');
            $table->string('Production');
            $table->string('Website');
            $table->string('Response');
            $table->json('json_response');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies');
    }
}
