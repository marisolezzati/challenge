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
        if($token->accessToken==""){
            $errorMessage = json_decode($token->message);
            return view('welcome', ['apiError'=>$errorMessage->error->code." - ".$errorMessage->error->message]);
        }
        else{
            session(['apiAccessToken' => $token->accessToken]);
            session(['tokenExp' => $token->exp]);
            return to_route('rates.index');
        }
    }
}
