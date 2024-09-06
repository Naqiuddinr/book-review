<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = $request -> input('title');
        $filter = $request -> input('filter', '');

        $books = Book::when($title, function ($query, $title) {
            return $query -> title($title); //yellow text title refer to scopeTitle at Book Model
        });

        $books = match($filter) { //match method is simlar to switch case in Node.js
            'popular_last_month' => $books->popularLastMonth(),
            'popular_last_6months' => $books->popularLast6Months(),
            'highest_rated_last_month' => $books->highestRatedLastMonth(),
            'highest_rated_last_6months' => $books->highestRatedLast6Months(),
            default => $books->latest()->withAvgRating()->withReviewCount(),
        };
        
        // $books = $books->get(); //$book will look at both the declaration above

        // $books = Cache::remember('books', 3600, fn() => $books->get()); //This method uses Facade Caching

        $cacheKey = 'books:' . $filter . ':' . $title;
        $books = cache()->remember($cacheKey, 3600, function () use($books) {
            return $books->get();
        });

        return view('books.index', ['books' => $books]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $cacheKey = 'book:' . $id;
 
        $book = cache()->remember($cacheKey, 3600, fn () => Book::with([
            'reviews' => function ($query) {
                return $query->latest();
            }
        ])->withAvgRating()->withReviewCount()->findOrFail($id));

        // $book = Book::with(['reviews' => function ($query) {
        //     return $query->latest();
        // }])->withAvgRating()->withReviewCount()->findOrFail($id);

        return view('books.show', ['book' => $book]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
