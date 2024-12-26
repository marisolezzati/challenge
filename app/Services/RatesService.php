<?php

namespace App\Services;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class RatesService
{
    public static function getRates($token, $vendorId, $params)
    {

        $url = "https://sandbox-api.shipprimus.com/api/v1/database/vendor/contract/{$vendorId}/rate"; 
        $requestUrl = $url.'?'.http_build_query($params);

        $client = new Client(['verify' => false]);

         try{
           
            $response = $client->request('GET', $requestUrl ,[
                    'headers' => 
                        [
                            'Authorization' => "Bearer {$token}",         
                            'User-Agent' => $_SERVER['HTTP_USER_AGENT'], 
                        ]
                    ,
                    ]
            );
            $contents = json_decode($response->getBody()->getContents());
            return $contents->data;
        } catch (\Exception $e) {
            $contents = (object)[];
            $contents->results = [];
            $contents->message = $e->getResponse()->getBody()->getContents();
            return $contents;
        }
    }
}