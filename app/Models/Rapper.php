<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rapper extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image_url', 'id_spotify', 'followers', 'popularity'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'rapper_user');
    }
}