<?php

namespace App\Model;

use App\Model\Gameplay;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'name', 
        'version'
    ];

    // Has many cgame played
    public function gameplays()
    {
        return $this->hasMany(Gameplay::class);
    }
}
