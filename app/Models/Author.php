<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Author extends Model
{
    use HasFactory;

    public function books(): BelongsToMany
    {
      return $this->belongsToMany(Book::class)->withTimestamps();
    }

    public function detail(): HasOne
    {
      return $this->hasOne(AuthorDetail::class);
    }
}
