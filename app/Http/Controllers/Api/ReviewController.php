<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{

    public function index()
    {
        $reviews =  Review::latest()->get();
        return $reviews;
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                'comment' => 'required',
                'rating' => 'required',
                'book_id' => 'required',
            ],
        );

        $user = Auth::user();
        $book = Book::find($request->book_id);

        // Check if the user is the owner of the book
        if (auth()->user()->id == $book->user_id) {
            return response()->json(['message' => 'You cannot review your own book.'], 403);
        }

        // Check if the user has already reviewed this book
        $existingReview = Review::where('user_id', auth()->user()->id)->where('book_id', $request->book_id)->first();

        if ($user && $book) {
            if ($existingReview) {
                return response()->json(['message' => 'You have already reviewed this book.'], 403);
            } else {
                // Create a new review
                $review = new Review();
                $review->rating = $request->rating;
                $review->comment = $request->comment;
                $review->book_id = $request->book_id;
                $review->user_id = auth()->user()->id;
                $review->save();

                return response()->json(['message' => 'Review saved successfully!']);
            }
        } else {
            return response()->json(['message' => 'User or book not found.'], 404);
        }
    }
}
