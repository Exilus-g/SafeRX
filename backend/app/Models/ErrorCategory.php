<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErrorCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    use HasFactory;

    protected $fillable = [
        'category_name',
        'description'
    ];
}
