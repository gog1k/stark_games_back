<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function userBoardAction(): Response
    {
        return response(
            Http::withHeaders([
                'authorization' => env('ACHIEVEMENTS_KEY')
            ])->get(env('ACHIEVEMENTS_SERVICE_URL') . '/api/user-room-link/' . auth()->user()->id));
    }
}
