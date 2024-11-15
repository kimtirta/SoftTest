<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['loan_id', 'fine_amount', 'paid'];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
