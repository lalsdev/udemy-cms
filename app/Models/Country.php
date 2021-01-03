<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
    // c'est quoi que je fais mal putaing
    public function products(){
        // deuxieme parametre contient country id
        return $this->hasManyThrough(Product::class, User::class);
    }
}
