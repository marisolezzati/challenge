<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Services\RatesService;
use App\Services\TokenService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class RatesController extends Controller
{
    
    public function index()
    {
        return view('rates.index');
    }

    public function store(Request $request)
    {
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

        $accessTokenInfo = $request->session()->only(['apiAccessToken', 'tokenExp']);
        
        $tokenExp = $accessTokenInfo['tokenExp'];
        if($tokenExp < time()){
            //token is expired, refresh
            $token = TokenService::refreshToken($accessTokenInfo['apiAccessToken']);
            session(['apiAccessToken' => $token->accessToken]);
            //session(['tokenExp' => $token->exp]); //commented out as it seems that the refresh method is not returning a new exp but only the token.
        }

        $result = RatesService::getRates($accessTokenInfo['apiAccessToken'], $data['vendorId'], $params);
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
        return view('rates.index', ['data'=>$data, 'error'=>$result->message]);
    }
}
