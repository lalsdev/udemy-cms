<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['price', 'stock', 'size'];
    use HasFactory;

    protected $table = 'product';
}
