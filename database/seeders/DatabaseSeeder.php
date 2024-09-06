<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       Book::factory(33)->create()->each(function ($book) {
            $numReviews = random_int(5, 30);

            Review::factory()->count($numReviews)
            ->good() //overriding method?
            ->for($book)
            ->create();

       }); //This code will generate 33 reviews, with random number of good reviews for each book.
       
       Book::factory(33)->create()->each(function ($book) {
            $numReviews = random_int(5, 30);

            Review::factory()->count($numReviews)
            ->average() //overriding method?
            ->for($book)
            ->create();

       }); //This code will generate 33 reviews, with random number of average reviews for each book.
       
       Book::factory(34)->create()->each(function ($book) {
            $numReviews = random_int(5, 30);

            Review::factory()->count($numReviews)
            ->bad() //overriding method?
            ->for($book)
            ->create();

       }); //This code will generate 34 reviews, with random number of bad reviews for each book.

    }
}
