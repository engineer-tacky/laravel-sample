<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use App\Models\Book;
use App\Models\Category;

class BookController extends Controller
{
    public function index(): Collection
    {
        return Book::all();
    }

    public function show(string $id): Book
    {
        return Book::findOrFail($id);
    }

    public function create(): View
    {
        $categories = Category::all();

        return view('admin/book/create', compact('categories'));
    }

    public function store(Request $request)
    {
        $book = new Book();
        $book->category_id = $request->category_id;
        $book->title = $request->title;
        $book->price = $request->price;
        $book->save();

        return $book;
    }
}
