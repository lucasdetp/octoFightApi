<?php

// app/Models/Battle.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Battle extends Model
{
    use HasFactory;

    protected $fillable = [
        'user1_id',
        'user2_id',
        'user1_rapper_id',
        'user2_rapper_id',
        'status',
        'winner_id',
    ];

    public function user1()
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'user2_id');
    }

    public function user1Rapper()
    {
        return $this->belongsTo(Rapper::class, 'user1_rapper_id');
    }

    public function user2Rapper()
    {
        return $this->belongsTo(Rapper::class, 'user2_rapper_id');
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }
}
