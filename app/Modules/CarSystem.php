<?php
namespace App\Modules;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

/**
 * 外部システム接続
 */
abstract class CarSystem
{
	public $token;

    public static function jatoaccessToken(){
        try{
            $url = 'https://webapi-demo.jato.com/jato.carspecs.api/authentication/login';
            //$url = env('CAR_API_URL')."/auth/login";
            $body = '{
            "email": "shubhamkumar@mobappswebsolutions.com",
            "password": "Mobapps@123"
            }';
            $client = new \GuzzleHttp\Client();
            $response = $client->request(
                'POST',
                $url,
                array(
                    "body"=>$body,
                    "headers" => array(
                        'content-type' => 'application/json'
                    ),
                )
            );
            //$responseBody['status_code'] = 200;
            $res = $response->getBody()->getContents();
            $restoken = json_decode($res);
            define("tokens", $restoken->token);
            $responseBody=$restoken->token;
            //$responseBody['body'] = $restoken->token;
           //print_r($restoken->token);
            //die;
            //define("tokens", $responseBody);
        }catch(\Exception $e) {
            //$responseBody['status_code'] = 400;
            //echo 12;
            $responseBody="";
        }
		return $responseBody;
    }


    public static function jatomarket(){
        try{
			$token = static::jatoaccessToken();
			$url = "https://webapi-demo.jato.com/jato.carspecs.api/markets/load";
			$client = new \GuzzleHttp\Client();
			$response = $client->request(
				'GET',
				$url,
				array(

					"headers" => array(
						"Authorization" => $token,
					),
				)
			);
            $responseBody['status_code'] = 200;
			$responseBody['body'] = $response->getBody()->getContents();
		}catch(\Exception $e) {
			$responseBody['status_code'] = 400;
		}
		return $responseBody;
    }

    public static function jatomake(){
        try{
			$token = static::jatoaccessToken();
			$url = "https://webapi-demo.jato.com/jato.carspecs.api/selections/makes";
            $body = '{
                "config": {
                    "applyInclExcl": false,
                    "applyInclExclFor": "makes",
                    "includeImages": true,
                    "includeTranslations": true,
                    "includeDetails": false
                },
                "input": [
                    {
                    "databaseName": "SSCGB_CS2002_PILOT_HIST"
                    }
                ]
            }';
			$client = new \GuzzleHttp\Client();
			$response = $client->request(
				'POST',
				$url,
				array(
                    "body"=>$body,
					"headers" => array(
						"Authorization" => $token,
                        'Content-Type'=> 'application/json'
					),
				)
			);
            $responseBody['status_code'] = 200;
			$responseBody['body'] = $response->getBody()->getContents();
		}catch(\Exception $e) {
            print_r($e->getmessage());
			$responseBody['status_code'] = 400;
		}
		return $responseBody;
    }

    public static function jatomodel($makekey){
        try{
			$token = static::jatoaccessToken();
			$url = "https://webapi-demo.jato.com/jato.carspecs.api/selections/models";
            $body = '{
                "config": {
                    "applyInclExcl": false,
                    "applyInclExclFor": "models",
                    "includeImages": true,
                    "includeTranslations": true,
                    "includeDetails": false
                },
                "input": [
                    {
                    "databaseName": "SSCGB_CS2002_PILOT_HIST",
                    "selections": [
                            {
                            "make": "'.$makekey.'"
                            }
                        ]
                    }
                ]
            }';
			$client = new \GuzzleHttp\Client();
			$response = $client->request(
				'POST',
				$url,
				array(
                    "body"=>$body,
					"headers" => array(
						"Authorization" => $token,
                        'Content-Type'=> 'application/json'
					),
				)
			);
            $responseBody['status_code'] = 200;
			$responseBody['body'] = $response->getBody()->getContents();
		}catch(\Exception $e) {
            print_r($e->getmessage());
			$responseBody['status_code'] = 400;
		}
		return $responseBody;
    }

    public static function jatocarbodies($brandname,$modelname,$yearID){
        try{
			$token = static::jatoaccessToken();
			$url = "https://webapi-demo.jato.com/jato.carspecs.api/selections/bodies";
            $body = '{
                "config": {
                    "applyInclExcl": false,
                    "applyInclExclFor": "bodies",
                    "includeImages": true,
                    "includeTranslations": true,
                    "includeDetails": false
                },
                "input": [
                    {
                    "databaseName": "SSCGB_CS2002_PILOT_HIST",
                    "selections": [
                            {
                            "make": "'.$brandname.'",
                            "model": "'.$modelname.'",
                            "modelYear": "'.$yearID.'"
                            }
                        ]
                    }
                ]
            }';
			$client = new \GuzzleHttp\Client();
			$response = $client->request(
				'POST',
				$url,
				array(
                    "body"=>$body,
					"headers" => array(
						"Authorization" => $token,
                        'Content-Type'=> 'application/json'
					),
				)
			);
            $responseBody['status_code'] = 200;
			$responseBody['body'] = $response->getBody()->getContents();
		}catch(\Exception $e) {
            print_r($e->getmessage());
			$responseBody['status_code'] = 400;
		}
		return $responseBody;
    }

    public static function jatocarvehicles($brandname,$modelname,$yearID){
        try{
			//$token = static::jatoaccessToken();
            $token = constant("tokens");
			$url = "https://webapi-demo.jato.com/jato.carspecs.api/selections/vehicles";
            $body = '{
                "config": {
                    "applyInclExcl": false,
                    "applyInclExclFor": "vehicles",
                    "includeImages": true,
                    "includeTranslations": true,
                    "includeDetails": false
                },
                "input": [
                    {
                    "databaseName": "SSCGB_CS2002_PILOT_HIST",
                    "selections": [
                            {
                            "make": "'.$brandname.'",
                            "model": "'.$modelname.'",
                            "modelYear": "'.$yearID.'"
                            }
                        ]
                    }
                ]
            }';
			$client = new \GuzzleHttp\Client();
			$response = $client->request(
				'POST',
				$url,
				array(
                    "body"=>$body,
					"headers" => array(
						"Authorization" => $token,
                        'Content-Type'=> 'application/json'
					),
				)
			);
            $responseBody['status_code'] = 200;
			$responseBody['body'] = $response->getBody()->getContents();
		}catch(\Exception $e) {
            print_r($e->getmessage());
			$responseBody['status_code'] = 400;
		}
		return $responseBody;
    }

    public static function jatocarphoto($vehicleID){
        try{
			//$token = static::jatoaccessToken();
            $token = constant("tokens");
			$url = "https://webapi-demo.jato.com/jato.carspecs.api/photos/defaults";
            $body = '{
            "databaseName": "SSCGB_CS2002_PILOT_HIST",
            "vehicleIds": [
                '.$vehicleID.'
            ]
            }';
			$client = new \GuzzleHttp\Client();
			$response = $client->request(
				'POST',
				$url,
				array(
                    "body"=>$body,
					"headers" => array(
						"Authorization" => $token,
                        'Content-Type'=> 'application/json'
					),
				)
			);
            $responseBody['status_code'] = 200;
			$responseBody['body'] = $response->getBody()->getContents();
		}catch(\Exception $e) {
            print_r($e->getmessage());
			$responseBody['status_code'] = 400;
		}
		return $responseBody;
    }
}
