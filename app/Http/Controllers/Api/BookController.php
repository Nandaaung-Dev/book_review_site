<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookDetailResource;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::orderBy('title')->get();
        return BookResource::collection($books)->additional(['message' => 'success']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $validateData = $request->validate([
        //     'title' => 'required',
        //     'short_description' => 'required',
        //     'long_description' => 'required',
        //     'published_at' => 'required',
        //     'user_id' => 'required',
        // ]);

        $request->validate(
            [
                'title' => 'required',
                'short_description' => 'required',
                'long_description' => 'required',
                'published_at' => 'required',
                'user_id' => 'required',
            ],
            [
                'user_id.required' => "The user field is required."
            ]
        );

        $book = new Book();
        $book->title = $request->title;
        $book->short_description = $request->short_description;
        $book->long_description = $request->long_description;
        $book->published_at = $request->published_at;
        $book->user_id = $request->user_id;
        $book->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::find($id);
        return ResponseHelper::success(new BookDetailResource($book));
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
