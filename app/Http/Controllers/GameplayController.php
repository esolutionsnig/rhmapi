<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Model\Gameplay;
use Illuminate\Http\Request;
use App\Http\Requests\DateRequest;
use App\Http\Requests\GameplayRequest;
use App\Http\Requests\DateRangeRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\GameplayResource;
use App\Http\Resources\GameplayCollection;

class GameplayController extends Controller
{
    
    /**
     * Add middleware to prevent unauthorzed user from make changes to db record
     * This middleware will be excluded for get requests i.e index and show URI
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Gameplay::with('game')->get();
        return response([
            'data' => $data
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GamePlayRequest $request)
    {
        $dt =  Carbon::now(); 
        $datenow = $dt->toDateString(); // Current Date Only

        $gptimes = 1;

        // Check if player has an active game that matches new game request
        $gameplay = Gameplay::where([
            ['user_id', $request->user_id],
            ['game_id', $request->game_id],
            ['date_played', $datenow]
        ])->first();
        
        if($gameplay) {
            $gptimes = $gameplay->times_played + 1;
            $gameplay->times_played = $gptimes;
            $gameplay->save();

            return response([
                'message' => 'New game play updated. You are now playing this game for the ' . $gptimes . ' time.',
                'data' => $gameplay
            ], Response::HTTP_CREATED);
        } else {
            $data = new Gameplay;
            $data->user_id = $request->user_id;
            $data->game_id = $request->game_id;
            $data->date_played = $datenow;
            $data->times_played = $gptimes;
            $data->save();

            return response([
                'message' => 'New game play successfully started.',
                'data' => $data
            ], Response::HTTP_CREATED);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Gameplay  $gameplay
     * @return \Illuminate\Http\Response
     */
    public function show(Gameplay $gameplay)
    {
        $data = Gameplay::where('id', $gameplay->id)->with('game')->first(); 
        return response([
            'data' => new GameplayResource($data)
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Gameplay  $gameplay
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gameplay $gameplay)
    {
        $gameplay->update($request->all());
        return response([
            'message' => 'Game play successfully updated',
            'data' => $gameplay
        ], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Gameplay  $gameplay
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gameplay $gameplay)
    {
        $gamepplay->delete();
        return response([
            'message' => 'Game deleted'
        ], Response::HTTP_OK);
    }

    /**
     * Get Game Plays by Date
     * @return [string] data
     */
    public function getByDate(DateRequest $request)
    {
        $data = Gameplay::where('date_played', $request->date)->with('game', 'user')->first();
        if($data) {
            return response([
                'data' => new GameplayResource($data)
            ], Response::HTTP_OK);
        } else {
            return response([
                'message' => 'Game play not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Get Game Plays by Date Range
     * @return [string] data
     */
    public function getByDateRange(DateRangeRequest $request)
    {
        $start_date = Carbon::parse($request->from)->toDateTimeString();
        $end_date = Carbon::parse($request->to)->toDateTimeString();
        
        $data = Gameplay::whereBetween('date_played', [$start_date,$end_date])->orderBy('id', 'desc')->get();
        if($data) {
            return GameplayResource::collection($data);
        } else {
            return response([
                'message' => 'Game play not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Get top 100 Players by Date Range with links
     * @return [string] data
     */
    public function getTopHundred(DateRangeRequest $request)
    {
        $start_date = Carbon::parse($request->from)->toDateTimeString();
        $end_date = Carbon::parse($request->to)->toDateTimeString();
        
        $data = Gameplay::whereBetween('date_played', [$start_date,$end_date])->orderBy('id', 'desc')->limit(100)->get();
        if($data) {
            return GameplayResource::collection($data);
        } else {
            return response([
                'message' => 'Game play not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
