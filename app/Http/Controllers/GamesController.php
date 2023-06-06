<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class GamesController extends Controller
{
    public function autocompleteListAction($mask = ''): Response
    {
        $response = Game
            ::where('name', 'LIKE', '%' . $mask . '%')
            ->limit(10)
            ->orderBy('rating', 'desc')
            ->get();

        return response($response->toArray());
    }

    public function setRatingAction($id = 0, Request $request): Response
    {
        $request->validate([
            'rate' => 'required|integer|in:1,2,3,4,5',
        ]);

        return response([$id, $request->rate]);
    }

    /**
     * @throws Exception
     */
    public function getAction($id = 0): Response
    {
        $response = Game
            ::where([
                'id' => $id,
            ])
            ->first();

        if (!$response) {
            throw new Exception('Game not found');
        }

        if (auth()->user()) {
            Http::withHeaders([
                'authorization' => env('ACHIEVEMENTS_KEY')
            ])->post(env('ACHIEVEMENTS_SERVICE_URL') . '/api/event/create/', [
                "user_id" => auth()->user()->id,
                "code" => "view_game",
            ]);
        }

        return response($response->toArray());
    }
}
