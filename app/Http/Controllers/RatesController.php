<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Services\RatesService;
use App\Services\TokenService;

class RatesController extends Controller
{
    

    public function store(Request $request)
    {
        $tokenExp = $request->tokenExp;
        if($tokenExp < time()){
            //token is expired, refresh
            $token = TokenService::refreshToken($request->accessToken);
            $accessToken = $token->accessToken;
            //$tokenExp = $token->exp; //commented out as it seems that the refresh method is not returning a new exp but only the token.
        }
        else{
            $accessToken = $request->accessToken;
        }

        $params  = [
            'originCity'  => $request->originCity,
            'originState'  =>  $request->originState,
            'originZipcode'  => $request->originZipcode,
            'originCountry'  => $request->originCountry,
            'destinationCity'  => $request->destinationCity,
            'destinationState'  => $request->destinationState,
            'destinationZipcode'  => $request->destinationZipcode,
            'destinationCountry'  => $request->destinationCountry,
            'UOM' =>  $request->UOM,
            'freightInfo'  =>  ["qty"  => $request->qty,
                                "weight" => $request->weight,
                                "weightType" => $request->weightType,
                                "length" => $request->length,
                                "width" => $request->width,
                                "height" => $request->height,
                                "class" => $request->class,
                                "hazmat" => $request->hazmat,
                                "commodity" => $request->commodity,
                                "dimType" => $request->dimType,
                                "stack" => $request->stack
                                ]
        ];
        $result = RatesService::getRates($accessToken, $request->vendorId, $params);
        $data = $result->results;
        $data = array_map(function($res) {
            $rate = [
                "CARRIER" => $res->name,
                "SERVICE LEVEL" =>  $res->serviceLevel,
                "RATE TYPE" =>  $res->rateType,
                "TOTAL" =>  $res->total,
                "TRANSIT TIME" =>  $res->transitDays,
            ];
            return $rate;
        }, $result->results);
        return view('rates.result', ['accessToken'=>$accessToken, 'tokenExp'=>$tokenExp, 'data'=>$data, 'error'=>$result->message]);
    }
}
