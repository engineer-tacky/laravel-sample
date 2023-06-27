<?php

namespace App\Policies;

use Illuminate\Support\Facades\Gate;
use App\Models\Admin;
use App\Models\Book;

class BookPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAny(Admin $admin): bool
    {
        return true;
    }

    public function view(Admin $admin, Book $book): bool
    {
        return Gate::allows('example-com-user');
    }

    public function create(Admin $admin): bool
    {
        return Gate::allows('example-com-user');
    }

    public function update(Admin $admin, Book $book): bool
    {
        return $admin->id === $book->admin_id;
    }

    public function delete(Admin $admin, Book $book): bool
    {
        return $admin->id === $book->admin_id;
    }
}
