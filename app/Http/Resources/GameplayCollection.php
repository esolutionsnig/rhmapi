<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\Resource;

class GameplayCollection extends Resource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'game_id' => $this->game_id,
            'date_played' => $this->date_played,
            'times_played' => $this->times_played,
            'joined_by' => $this->joined_by,
            'user' => $this->user,
            'game' => $this->game,
            'href' => [
                'link' => route('gameplays.show',$this->id)
            ]
        ];
    }
}
