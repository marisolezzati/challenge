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
        
        $data = $request->validate([
            'vendorId'  => ['required','string'],
            'originCity'  => ['required','string'],
            'originState'  =>  ['required','string','size:2'],
            'originZipcode'  => ['required','string'],
            'originCountry'  => ['required','string','size:2'],
            'destinationCity'  => ['required','string'],
            'destinationState'  => ['required','string','size:2'],
            'destinationZipcode'  => ['required','string'],
            'destinationCountry'  => ['required','string','size:2'],
            'UOM' =>  ['required','string','size:2'],
            "qty"  =>  ['required','string'],
            "weight" =>  ['required','numeric'],
            "weightType" =>  ['required','string'],
            "length" =>  ['required','numeric'],
            "width" =>  ['required','numeric'],
            "height" =>  ['required','numeric'],
            "class" =>  ['required','numeric'],
            "hazmat" =>  ['required','numeric'],
            "dimType" =>  ['required','string'], 
            "stack" =>  ['required'],
            "comodity" =>  ['nullable'],
        ]);
        $params  = [
            'originCity'  => $data['originCity'],
            'originState'  =>  $data['originState'],
            'originZipcode'  => $data['originZipcode'],
            'originCountry'  => $data['originCountry'],
            'destinationCity'  => $data['destinationCity'],
            'destinationState'  => $data['destinationState'],
            'destinationZipcode'  => $data['destinationZipcode'],
            'destinationCountry'  => $data['destinationCountry'],
            'UOM' =>  $data['UOM'],
            'freightInfo'  => [ 
                    ["qty"  => $data['qty'],
                    "weight" => $data['weight'],
                    "weightType" => $data['weightType'],
                    "length" => $data['length'],
                    "width" => $data['width'],
                    "height" => $data['height'],
                    "class" => $data['class'],
                    "hazmat" => $data['hazmat'],
                    "commodity" => (isset($data['commodity']))?$data['commodity']:"",
                    "dimType" => $data['dimType'],
                    "stack" => $data['stack']
                ],
            ]
        ];
        $result = RatesService::getRates($accessToken, $data['vendorId'], $params);
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
