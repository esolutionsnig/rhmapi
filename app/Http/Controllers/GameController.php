<?php

namespace App\Http\Controllers;

use App\Model\Game;
use Illuminate\Http\Request;
use App\Http\Requests\GameRequest;
use Symfony\Component\HttpFoundation\Response;

class GameController extends Controller
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
        $data = Game::with('gameplays')->get();
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
    public function store(GameRequest $request)
    {
        $data = new Game;
        $data->name = $request->name;
        $data->version = $request->version;
        $data->save();

        return response([
            'message' => 'New game successfully added.',
            'data' => $data
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function show(Game $game)
    {
        $data = Game::where('id', $game->id)->with('gameplays')->first();
        return response([
            'data' => $data
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Game $game)
    {
        $game->update($request->all());
        return response([
            'message' => 'Game successfully updated',
            'data' => $game
        ], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function destroy(Game $game)
    {
        $game->delete();
        return response([
            'message' => 'Game deleted'
        ], Response::HTTP_OK);
    }
}
