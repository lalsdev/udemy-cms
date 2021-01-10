<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['delete_at'];
    protected $fillable = ['price', 'stock', 'size', 'brand'];
    protected $table = 'product';
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function country(){
        return $this->hasOne(Country::class);
    }
    public function photos(){
        return $this->morphMany('App\Models\Photo', 'picturable');
    }
}
