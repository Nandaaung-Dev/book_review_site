<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Resources\BookResource;

class ReviewController extends Controller
{

    public function index()
    {
        $reviews =  Review::all();
        return $reviews;
    }
    public function store(Request $request)
    {
        $user = User::find($request->user_id);
        $book = Book::find($request->book_id);

        $existingReview = Review::where('user_id', $request->user_id)->where('book_id', $request->book_id)->first();

        if ($user && $book) {
            if ($existingReview) {
                return "You have already reviewed this book.";
            } else {
                // Create a new review
                $review = new Review([
                    'user_id' => $request->user_id,
                    'book_id' => $request->book_id,
                    'rating' => $request->rating,
                    'comment' => $request->comment,
                ]);
                $book->reviews()->save($review);

                return "Review saved successfully!";
            }
        } else {
            return "User or book not found.";
        }
    }
}
