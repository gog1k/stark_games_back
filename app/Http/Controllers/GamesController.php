<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
    public function getAction($id = 0): Response
    {
        $response = Game
            ::where([
                'id' => $id,
            ])
            ->first();

        return response($response->toArray());
    }
}
