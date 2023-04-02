<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function getListAction(): Response
    {
        return response(User::get()->map(function ($user) {
            return array_merge($user->toArray(), [
                'groups' => $user->getGroupNames(),
            ]);
        }));
    }
}
