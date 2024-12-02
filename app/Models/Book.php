<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'genre',
        'available_copies',
    ];

    // Add an accessor to determine if the book is available
    public function getIsAvailableAttribute()
    {
        return $this->available_copies > 0;
    }
}
