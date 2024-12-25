<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Services\TokenService;

class UsersController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'username'=> ['required','string'],
            'password'=> ['required','string'],
        ]);
        $token = TokenService::getToken($data['username'], $data['password']);
        return view('rates.result', ['accessToken'=>$token->accessToken, 'tokenExp'=>$token->exp]);
    }
}
