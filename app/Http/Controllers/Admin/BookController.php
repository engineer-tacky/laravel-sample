<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Book;

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
}
