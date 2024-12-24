<?php

namespace App\Services;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class TokenService
{
    public static function getToken($username, $password)
    { 
        $client = new Client(['verify' => false]);

        $url = "https://sandbox-api.shipprimus.com/api/v1/login";

        try {
            $response = $client->request('POST',$url,[
                    'form_params' => [
                        'username' => $username,
                        'password' => $password,
                ]
            ]);

            $token = json_decode($response->getBody()->getContents());
            return $token->data;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public static function refreshToken($oldToken)
    {
        $client = new Client(['verify' => false]);

        $url = "https://sandbox-api.shipprimus.com/api/v1/refreshtoken";

        try {
            $response = $client->request('POST',$url,[
                    'form_params' => [
                        'token' => $oldToken,
                ]
            ]);

            $token = json_decode($response->getBody()->getContents());

            return $token->data;
        } catch (\Exception $e) {
            return $e;
        }
    }
}
