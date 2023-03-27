<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rules;


class AuthController extends Controller
{
    public function signinAction(Request $request)
    {
        if (auth()->attempt($request->all())) {
            return response($this->getUserInfo(auth()->user()), Response::HTTP_OK);
        }

        return response([
            'message' => 'This User does not exist'
        ], Response::HTTP_UNAUTHORIZED);
    }


    public function signupAction(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response($this->getUserInfo($user));
    }

    private function getUserInfo($user) {
        return [
            'user' => $user,
            'groups' => array_column($user->groups->toArray(), 'name'),
            'access_token' => $user->createToken('authToken', ['admin'])->accessToken
        ];
    }
}
