<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admin'; // Explicitly set the table to 'admin'
    protected $fillable = ['name', 'email', 'password'];
    protected $rules = [
        'email' => 'required|email|max:255', // Email must be valid and not exceed 255 characters
        'password' => 'required|min:8|max:64', // Password must be between 8 and 64 characters
    ];
    protected $messages = [
        'email.required' => 'The email field is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.max' => 'The email address must not exceed 255 characters.',
        'password.required' => 'The password field is required.',
        'password.min' => 'The password must be at least 8 characters.',
        'password.max' => 'The password must not exceed 64 characters.',
    ];
}