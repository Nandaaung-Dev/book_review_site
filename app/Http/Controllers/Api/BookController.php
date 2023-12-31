<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\BookDetailResource;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::latest()->get();
        return BookResource::collection($books)->additional(['message' => 'success']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'title' => 'required|unique:books,title',
                'short_description' => 'required',
                'long_description' => 'required',
                'published_at' => 'required',
            ],
        );
        $book = new Book();
        $book->title = $request->title;
        $book->short_description = $request->short_description;
        $book->long_description = $request->long_description;
        $book->published_at = $request->published_at;
        $book->user_id = auth()->user()->id;
        $book->save();

        return response()->json(['message' => 'Book saved successfully!'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $book = Book::with('reviews')->find($id);

        if ($book) {
            return ResponseHelper::success(new BookDetailResource($book));
        } else {
            return response()->json(['message' => 'Book not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $book = Book::findOrFail($id);

        // Check if the authenticated user is the author of the book
        if (auth()->user()->id !== $book->user_id) {
            return response()->json(['message' => 'Only author can update.'], 403);
        }

        $request->validate([
            'title' => 'required',
            'short_description' => 'required',
            'long_description' => 'required',
            'published_at' => 'required',
        ]);

        $book->update($request->all());

        return response()->json(['message' => 'Book updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::findOrFail($id);

        // Check if the authenticated user is the author of the book
        if (auth()->user()->id !== $book->user_id) {
            return response()->json(['message' => 'Only author can delete.'], 403);
        }

        $book->delete();
        return response()->json(['message' => 'Book deleted successfully']);
    }
}
