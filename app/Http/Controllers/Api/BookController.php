<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Books;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function index()
    {
        $books = Books::latest()->paginate(5);

        return new BookResource(true, 'Books List', $books);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'author' => 'required',
            'description' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('cover_image');
        $image->storeAs('public/books', $image->hashName());

        $book = Books::create([
            'title' => $request->title,
            'author' => $request->author,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'cover_image' => $image->hashName(),
        ]);

        return new BookResource(true, 'Book added!', $book);
    }

    public function show(Books $book)
    {
        return new BookResource(true, 'Book found!', $book);
    }

    public function update(Request $request, Books $book)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'author' => 'required',
            'description' => 'required',
            'price' => 'required',
            'stock' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->hasFile('cover_image')) {

            $image = $request->file('cover_image');
            $image->storeAs('public/books', $image->hashName());

            Storage::delete('public/books/' . $book->cover_image);

            $book->update([
                'title' => $request->title,
                'author' => $request->author,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'cover_image' => $image->hashName(),
            ]);
        } else {
            $book->update([
                'title' => $request->title,
                'author' => $request->author,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
            ]);
        }

        return new BookResource(true, 'Book updated!', $book);
    }

    public function destroy(Books $book)
    {
        Storage::delete('public/books/' . $book->cover_image);
        $book->delete();
        return new BookResource(true, "Book deleted!", null);
    }
}
