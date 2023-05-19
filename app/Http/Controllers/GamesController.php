<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Response;

class GamesController extends Controller
{
    public function autocompleteListAction($mask = ''): Response
    {
        $response = Game
            ::where('name', 'LIKE', '%' . $mask . '%')
            ->limit(10)
            ->get();

        return response($response->toArray());
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
