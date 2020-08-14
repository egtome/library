<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Author;
use App\Book;
use Faker\Generator as Faker;

$factory->define(Book::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        //'author_id' => factory(Author::class)->create(), /* Always create an Author */
        'author_id' => factory(Author::class)->create(), /* Only create an Author if not author_id is passed */
    ];
});
