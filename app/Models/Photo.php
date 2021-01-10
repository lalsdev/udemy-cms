<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = ['picturable_id', 'picturable_type', 'path'];

    public function imageable(){
        return $this->morphTo();
    }
}
