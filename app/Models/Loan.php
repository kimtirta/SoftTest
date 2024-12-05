<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'book_id', 'due_date', 'returned_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
    
    public function getIsOverdueAttribute()
    {
        return !$this->returned_date && now()->greaterThan($this->due_date);
    }
}

