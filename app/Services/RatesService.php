<?php

namespace App\Services;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class RatesService
{
    public static function getRates($token, $vendorId, $params)
    {

        $url = "https://sandbox-api.shipprimus.com/api/v1/database/vendor/contract/{$vendorId}/rate"; //https://reqres.in/api/users?page=2
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
            //server retunrs a 403,"message":"Domain is not whitelisted" use sample code
            $contents = json_decode('{
                "results": [
                {
                "id": "112233aa",
                "name": "Carrier Name",
                "SCAC": "",
                "rating": 2,
                "serviceLevel": "Service Level Name Example",
                "serviceLevelCode": "",
                "transitDays": 5,
                "rateBreakdown": [
                {
                "name": "FREIGHT CHARGE",
                "total": 999
                }
                ],
                "rateRemarks": [
                "Remarks example"
                ],
                "total": 999,
                "rateType": "DRAYAGE",
                "iconUrl": "https://dev.shipprimus.com/images/blank64x32.png",
                "responseTime": 1.01
                },
                {
                "id": "999999",
                "name": "Carrier Example",
                "SCAC": "",
                "rating": 4,
                "serviceLevel": "Service Level Second Example",
                "serviceLevelCode": "",
                "transitDays": 5,
                "rateBreakdown": [
                {
                "name": "FREIGHT CHARGE",
                "total": 1999
                }
                ],
                "rateRemarks": [
                "Remarks example"
                ],
                "total": 1999,
                "rateType": "DRAYAGE",
                "iconUrl": "https://dev.shipprimus.com/images/blank64x32.png",
                "responseTime": 1.01
                }
                ],
                "message": ""
                }');
            $contents->message = $e->getResponse()->getBody()->getContents();
            return $contents;
        }
    }
}