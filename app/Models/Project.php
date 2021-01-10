<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];
    // si pas cette relation est ce que many through fonctionne ? OUI
    public function humans()
    {
        return $this->hasMany(Human::class);
    }
    // trouve les taches relié a un projet lié a un humain
    public function tasks()
    {
        return $this->hasManyThrough(Task::class, Human::class);
    }
}
