<?php

namespace App\Model;

use App\User;
use App\Model\Game;
use Illuminate\Database\Eloquent\Model;

class Gameplay extends Model
{
    protected $fillable = [
        'user_id', 
        'game_id', 
        'date_played', 
        'times_played', 
        'joined_by'
    ];

    // Many to one relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Many to one relationship with game
    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
