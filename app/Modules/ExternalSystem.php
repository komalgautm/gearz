<?php
namespace App\Modules;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

/**
 * 外部システム接続
 */
abstract class ExternalSystem
{
	public $token;
    
    public static function accessToken(){
        $url = 'https://carapi.app/api/auth/login';
        //$url = env('CAR_API_URL')."/auth/login";
        $body = '{
            "api_token": "dcdb9450-da42-4ab6-a7a6-03d91ba06087",
            "api_secret": "6dc3bc62e4d31b2f28416ff4912f8c74"
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
		 $responseBody = $response->getBody()->getContents();
		define("tokens", $responseBody);
		//$token = $responseBody;
		return $responseBody;
    }

	public static function get_model($make_id,$year)
	{ 
		try{      
			$token = static::accessToken();
			$url = "https://carapi.app/api/models?year=$year&make_id=$make_id";
			//$url = env('CAR_API_URL')."models?year=$year&make_id=$make_id"; die;
			$client = new \GuzzleHttp\Client();
			$response = $client->request(
				'GET',
				$url,
				array(
					"headers" => array(
						"Authorization" => "Bearer ".$token,
					),
				)
			);
			$responseBody = $response->getBody()->getContents();
		}catch(\Exception $e) {
			$responseBody['status_code'] = 400;
		}
		return $responseBody;
	}

    public static function get_trim($make_id,$year,$make_model_id)
	{  
		//$token = constant("tokens");
        $token = static::accessToken();
		$url = "https://carapi.app/api/trims?year=$year&make_model_id=$make_model_id&make_id=$make_id";
		//$url = env('CAR_API_URL')."trims?year=$year&make_model_id=$make_model_id&make_id=$make_id";
		$client = new \GuzzleHttp\Client();
		$response = $client->request(
			'GET',
			$url,
			array(
				"headers" => array(
					"Authorization" => "Bearer ".$token,
				),
			)
		);
		$responseBody = $response->getBody()->getContents();
		return $responseBody;
	}

    public static function get_vehicle_detail($vehicle_id)
	{       
        //$token = static::accessToken();
		$token = constant("tokens");
		$url = "https://carapi.app/api/trims/".$vehicle_id;
		//$url = env('CAR_API_URL')."trims/".$vehicle_id;
		$client = new \GuzzleHttp\Client();
		$response = $client->request(
			'GET',
			$url,
			array(
				"headers" => array(
					"Authorization" => "Bearer ".$token,
				),
			)
		);
		$responseBody = $response->getBody()->getContents();
		return $responseBody;
	}

	public static function get_vin_decoder($vin){
		try{
			$token = static::accessToken();
			$url = "https://carapi.app/api/vin/".$vin;
			//$url = env('CAR_API_URL')."vin/".$vin;
			$client = new \GuzzleHttp\Client();
			$response = $client->request(
				'GET',
				$url,
				array(
					"headers" => array(
						"Authorization" => "Bearer ".$token,
					),
				)
			);
			$responseBody['status_code'] = $response->getStatusCode();
			$responseBody['body'] = $response->getBody()->getContents();
			//$responseBody = $response->getBody()->getContents();
		}catch(\Exception $e) {
			$responseBody['status_code'] = 400;
		}
		return $responseBody;
	}

	public static function getvin_decoder($vin){
		try{			
			$url = "https://api.carsxe.com/specs?key=f4yq6riyc_y1ycgu7vb_o5zaemyen&vin=".$vin;
			//$url = env('CARXE_URL')."specs?key=".env('CARXE_KEY')."&vin=".$vin;
			$client = new \GuzzleHttp\Client();
			$response = $client->request(
				'GET',
				$url,
				array(
					"headers" => array(
						'Content-Type' => 'application/x-www-form-urlencoded'
					),
				)
			);
			$responseBody['status_code'] = $response->getStatusCode();
			$responseBody['body'] = $response->getBody()->getContents();
			//$responseBody = $response->getBody()->getContents();
		}catch(\Exception $e) {
			$responseBody['status_code'] = 400;
		}
		return $responseBody;
	}

	public static function translate($string){
		try{			
			$url = "https://api-free.deepl.com/v2/translate";
			//$url = env('TRANSLATE_URL')."translate";
			$client = new \GuzzleHttp\Client();
			$body = '{
				"text": [
				  "'.$string.'"
				],
				"target_lang": "AR"
			  }';
			//   print_r($body);
			//   die;
			$response = $client->request(
				'POST',
				$url,
				array(
					"headers" => array(
						'Authorization' => "DeepL-Auth-Key ".env('TRANSLATE_KEY'),
  						'Content-Type' => 'application/json'
					),
					"body" => $body
				)
			);
			$responseBody['status_code'] = $response->getStatusCode();
			$responseBody['body'] = $response->getBody()->getContents();
			//$responseBody = $response->getBody()->getContents();
		}catch(\Exception $e) {
			//print_r($e->getMessage());
			$responseBody['status_code'] = 400;
		}
		return $responseBody;
	}

	public static function vehicle_image($make,$model,$trim,$year){
		try {		
			// $year = 2000;
			// $make = 'Hyundai';
			// $model = 'Accent';
			// $trim = 'GS 2dr Hatchback Manual';

			$url = "https://api.vehicledatabases.com/vehicle-media/v2/$year/$make/$model/" . urlencode($trim);

			$client = new \GuzzleHttp\Client();

			$response = $client->request('GET', $url, [
				'headers' => [
					'x-Authkey' => '51ba78244df411f0b4f60242ac120002',
					'Accept' => 'application/json',
				],
			]);

			// First decode the outer JSON response
			$responseBody = json_decode($response->getBody(), true);

			return $responseBody;
			
		}catch(\Exception $e) {
			$response = $e->getResponse();
			if ($response) {
				$body = $response->getBody()->getContents();
				$error = json_decode($body, true);
				return $error ?? response()->json(["statusCode"=> 500, "message"=> "Unknown error"]);
			} else {
				return $e->getMessage(); // fallback if no response
			}
		}

	}

	public static function trim_list($year,$make,$model){
		try {		

			$url = "https://api.vehicledatabases.com/vehicle-media/options/v3/trim/$year/$make/$model";

			$client = new \GuzzleHttp\Client();

			$response = $client->request('GET', $url, [
				'headers' => [
					'x-Authkey' => '51ba78244df411f0b4f60242ac120002',
					'Accept' => 'application/json',
				],
			]);

			// First decode the outer JSON response
			$responseBody = json_decode($response->getBody(), true);

			return $responseBody;
			
		}catch(\Exception $e) {
			$response = $e->getResponse();
			if ($response) {
				$body = $response->getBody()->getContents();
				$error = json_decode($body, true);
				return $error ?? response()->json(["statusCode"=> 500, "message"=> "Unknown error"]);
			} else {
				return $e->getMessage(); // fallback if no response
			}
		}

	}

	public static function car_360View_by_Spyne($imageArrayDB,$stockNumber){
		try {
			ini_set('max_execution_time', 300);
			$apiKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJlbnRlcnByaXNlSWQiOiJjZTlmZGU1MmUiLCJ0ZWFtSWQiOiI1ZTE5MDkwYjNjIiwidXNlcklkIjoiMTYxMGE3MmUiLCJzZWNyZXRLZXkiOiJhNmQ1YTAyMmEyNDQ0Zjc2OTgzMTJiNGY5Yjc4M2ZkOCIsImlhdCI6MTc1MzQyOTQwOCwiZXhwIjoxODQ4MDM3NDA4fQ.y9apGcx6CoMeOOsQPaZMI4dMv0pzQHpN52ScbNc_BPE';
			
			$endpoint = 'https://api.spyne.ai/api/pv1/merchandise/process';
			
			// $baseImageUrl = url('public/images');
			// $imageData = [];
			// foreach ($imageArrayDB as $imageValue) {
			// 	$imageData[] = [
			// 		"url" => "{$baseImageUrl}/$imageValue->image"
			// 	];
			// }
			
			// return $imageData;
			
			// $baseImageUrl = "http://44.199.99.55/public/images";
			$baseImageUrl = "https://mobappssolutions.in/GearzApp/public/images";

			// Prepare image data - for 360 spin, you typically need more images (8-24+ images)
			$imageData = [];
			for ($i = 0; $i < 36; $i++) {
				$imageData[] = [
					"url" => "{$baseImageUrl}/car360_1752065036_{$i}.jpg"
				];
			}
			
			// MODIFIED: Payload for 360 spin generation
			$payload = [
				"vin" => "",
				"stockNumber" => $stockNumber,
				"registrationNumber" => "",
				"media" => [
					"image" => true,      
					"spin" => true,        
					"featureVideo" => false
				],
				"mediaInput" => [
					"imageData" => $imageData
				],
				"processingDetails" => [
					"backgroundId" => "60070",
					"frames" => count($imageData),
					"hotspots" => true             
				]
			];
			
			// Initial request to Spyne
			$response = Http::timeout(180)
				->withToken($apiKey)
				->post($endpoint, $payload);
				
			if (!$response->successful()) {
				return response()->json([
					'success' => false,
					'message' => 'Submission to Spyne failed',
					'status_code' => $response->status(),
					'error' => $response->json(),
					'raw' => $response->body()
				], $response->status());
			}
			
			$responseJson = $response->json();
			$dealerVinID = data_get($responseJson, 'data.dealerVinID');
			
			if (!$dealerVinID) {
				return response()->json([
					'success' => false,
					'message' => 'dealerVinID not found in response',
					'raw_response' => $responseJson
				], 422);
			}

			// $webhookpayload = [
			// 	'stockNumber' => 'STOCK_ABARTH500ESSEESSE2008',
			// 	"dealerVinId" => "dfda6806-f73b-4398-bfea-4b9a15c10da0",  // Your custom reference ID
			// 	"webhookUrl" => "http://localhost/gearz/Webhook.php"
			// ];

			$webhookpayload = [
				'stockNumber' => $stockNumber,
				"dealerVinId" => $dealerVinID,  // Your custom reference ID
				"webhookUrl" => "http://localhost/gearz/Webhook.php"
			];

			$query = http_build_query($webhookpayload);
			$retrieveEndpoint = "https://api.spyne.ai/api/pv1/merchandise?$query";

			$webhookresponse = Http::timeout(180)
						->withToken($apiKey)
						->get($retrieveEndpoint);


			
			// Correct endpoint with proper parameter name
			// $retrieveEndpoint = "https://api.spyne.ai/api/pv1/merchandise?dealerVinId={$dealerVinID}";
			
			// $maxAttempts = 15; 
			// $attempt = 0;
			// $result = null;
			
			// do {
			// 	sleep(15); 
			// 	$result = Http::timeout(180)
			// 		->withToken($apiKey)
			// 		->get($retrieveEndpoint);
				
			// 	$attempt++;
				
			// 	if ($result->successful()) {
			// 		$resultData = $result->json();
					
			// 		// Check if we have the expected data structure
			// 		if (isset($resultData['mediaData'])) {
			// 			$imageData = $resultData['mediaData']['image'] ?? [];
			// 			$spinData = $resultData['mediaData']['spin'] ?? [];
						
			// 			// Check image processing status
			// 			$imageAiStatus = $imageData['aiStatus'] ?? null;
			// 			$imageQcStatus = $imageData['qcStatus'] ?? null;
						
			// 			// Check 360 spin processing status
			// 			$spinAiStatus = $spinData['aiStatus'] ?? null;
			// 			$spinQcStatus = $spinData['qcStatus'] ?? null;
			// 			$iframeUrl = $spinData['irameUrl'] ?? null; // Note: API uses 'irameUrl' not 'iframeUrl'
			// 			$thumbnailUrl = $spinData['thumbnailUrl'] ?? null;
						
			// 			// Check if both image and spin processing are complete
			// 			$imageComplete = ($imageAiStatus === 'DONE' && $imageQcStatus === 'qc_done');
			// 			$spinComplete = ($spinAiStatus === 'DONE' && $spinQcStatus === 'qc_done' && !empty($iframeUrl));
						
			// 			if ($imageComplete && $spinComplete) {
			// 				break;
			// 			}
			// 		}
			// 	}
			// } while ($attempt < $maxAttempts);
			
			// // Check final result
			// if (!$result || !$result->successful()) {
			// 	return response()->json([
			// 		'success' => false,
			// 		'message' => 'Failed to retrieve processed media',
			// 		'dealerVinID' => $dealerVinID,
			// 		'attempts' => $attempt,
			// 		'endpoint_used' => $retrieveEndpoint,
			// 		'last_response' => optional($result)->json()
			// 	], optional($result)->status() ?? 500);
			// }
			
			// $finalData = $result->json();
			
			// // Validate the response structure
			// if (!isset($finalData['mediaData'])) {
			// 	return response()->json([
			// 		'success' => false,
			// 		'message' => 'Unexpected response structure',
			// 		'dealerVinID' => $dealerVinID,
			// 		'attempts' => $attempt,
			// 		'full_response' => $finalData
			// 	], 422);
			// }
			
			// $imageData = $finalData['mediaData']['image'] ?? [];
			// $spinData = $finalData['mediaData']['spin'] ?? [];
			
			// $imageAiStatus = $imageData['aiStatus'] ?? null;
			// $imageQcStatus = $imageData['qcStatus'] ?? null;
			// $spinAiStatus = $spinData['aiStatus'] ?? null;
			// $spinQcStatus = $spinData['qcStatus'] ?? null;
			// $iframeUrl = $spinData['spinIframe'] ?? null; // Note: API uses 'irameUrl' not 'iframeUrl'
			// $thumbnailUrl = $spinData['thumbnailUrl'] ?? null;
			
			// // Check completion status
			// $imageComplete = ($imageAiStatus === 'DONE' && $imageQcStatus === 'qc_done');
			// $spinComplete = ($spinAiStatus === 'DONE' && $spinQcStatus === 'qc_done' && !empty($iframeUrl));
			
			// return response()->json([
			// 	'success' => 'true',
			// 	'message' => '360 Video URL',
			// 	'url' => $iframeUrl,
			// 	'thumbnail' => $thumbnailUrl,
			// 	'full_response' => $finalData
			// ]);

			// if (!$imageComplete || !$spinComplete) {
			// 	return response()->json([
			// 		'success' => false,
			// 		'message' => 'Media processing not complete',
			// 		'dealerVinID' => $dealerVinID,
			// 		'attempts' => $attempt,
			// 		'status' => [
			// 		'status' => [
			// 			'image' => [
			// 				'aiStatus' => $imageAiStatus,
			// 				'qcStatus' => $imageQcStatus,
			// 				'complete' => $imageComplete
			// 			],
			// 			'spin' => [
			// 				'aiStatus' => $spinAiStatus,
			// 				'qcStatus' => $spinQcStatus,
			// 				'complete' => $spinComplete,
			// 				'hasIframeUrl' => !empty($iframeUrl)
			// 			]
			// 		],
			// 		],
			// 		'full_response' => $finalData
			// 	], 422);
			// }
			
			// return response()->json([
			// 	'success' => true,
			// 	'dealerVinID' => $dealerVinID,
			// 	'attempts' => $attempt,
			// 	'imageData' => $imageData['imageData'] ?? [],
			// 	'spinUrl' => $iframeUrl,
			// 	'thumbnailUrl' => $thumbnailUrl,
			// 	'spinData' => $spinData,
			// 	'status' => [
			// 		'image' => [
			// 			'aiStatus' => $imageAiStatus,
			// 			'qcStatus' => $imageQcStatus
			// 		],
			// 		'spin' => [
			// 			'aiStatus' => $spinAiStatus,
			// 			'qcStatus' => $spinQcStatus
			// 		]
			// 	],
			// 	'full_response' => $finalData
			// ]);
			
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Exception occurred: ' . $e->getMessage(),
				'trace' => $e->getTraceAsString()
			], 500);
		}
	}


	public static function car_image($make,$model,$year,$color){
		try{						
			$url = "https://api.carsxe.com/images?key=f4yq6riyc_y1ycgu7vb_o5zaemyen&make=".$make."&model=".$model."&year=".$year."&color=".$color."&format=json";
			//$url = env('CARXE_URL')."images?key=".env('CARXE_KEY')."&make=".$make."&model=".$model."&year=".$year."&color=".$color."&format=json";
			$client = new \GuzzleHttp\Client();
			$response = $client->request(
				'GET',
				$url,
				array(
					"headers" => array(
						'Content-Type' => 'application/x-www-form-urlencoded'
					),
				)
			);
			$responseBody['status_code'] = $response->getStatusCode();
			$responseBody['body'] = $response->getBody()->getContents();
			//$responseBody = $response->getBody()->getContents();
		}catch(\Exception $e) {
			$responseBody['status_code'] = 400;
		}
		return $responseBody;
	}

	public static function chat($message)
    {
		try{
			$client = new \GuzzleHttp\Client();
			//$apiKey = env('OPENAI_API_KEY');
			$apiKey = "sk-proj-uQJwkBBNJ9IUWgnz189yT3BlbkFJQaixbxMHkIfjOdRPKQ5T";
        	$url = "https://api.openai.com/v1/chat/completions";
        	//$url = env('OPENAI_API_URL');
			$response = $client->post($url, [
				'headers' => [
					'Content-Type' => 'application/json',
					'Authorization' => 'Bearer ' . $apiKey,
				],
				'json' => [
					'model' => "gpt-4o-mini",
					'messages' => [
						['role' => 'system', 'content' => 'You are a helpful assistant.'],
						['role' => 'user', 'content' => $message],
					],
				],
			]);
			$responseBody['status_code'] = $response->getStatusCode();
			$responseBody['body'] = $response->getBody()->getContents();
			//$responseBody = json_decode($response->getBody()->getContents(), true);

		}catch(\Exception $e) {
			//print_r($e->getMessage());
			$responseBody['status_code'] = 400;
		}
		return $responseBody;
    }

	public static function get_car_detail($car_brand,$car_model,$car_class,$car_year)
    {
		try{
			//$url = "https://virdim.com/GearZ/car_details?car_brand=".$car_brand."&car_model=".$car_model."&car_class=".$car_class."&car_year=".$car_year;
			$url = "https://ra2fat.com/_vps/GearZ/get_car_specifications?car_brand=".$car_brand."&car_model=".$car_model."&car_class=".$car_class."&car_year=".$car_year;
			$client = new \GuzzleHttp\Client();
			$response = $client->request(
				'GET',
				$url,
				array(
					"headers" => array(
						'Content-Type' => 'application/x-www-form-urlencoded'
					),
				)
			);
			$responseBody['status_code'] = $response->getStatusCode();
			$responseBody['body'] = $response->getBody()->getContents();
		}catch(\Exception $e) {
			$responseBody['status_code'] = 400;
		}
		//print_r($responseBody);
		
		return $responseBody;
    }

	//resize image
	public static function resizeImage($resourceType,$image_width,$image_height,$resizeWidth,$resizeHeight) {
        // $resizeWidth = 100;
        // $resizeHeight = 100;
        $imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);
        imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
        return $imageLayer;
    }

    public static function saveresizeimage($new_width,$new_height,$fileName,$uploadPath,$fileExt,$imgname){
        //$new_width = 720;
        //$new_height = 480;
        //$fileName = $_FILES['image']['tmp_name'];
        $sourceProperties = getimagesize($fileName);
        $resizeFileName = time();
        //$uploadPath = "./public/img/banners/";
        //$fileExt = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $uploadImageType = $sourceProperties[2];
        $sourceImageWidth = $sourceProperties[0];
        $sourceImageHeight = $sourceProperties[1];
        switch ($uploadImageType) {
            case IMAGETYPE_JPEG:
                $resourceType = imagecreatefromjpeg($fileName); 
                $imageLayer = static::resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight,$new_width,$new_height);
                imagejpeg($imageLayer,$uploadPath.$imgname.$resizeFileName.'.'. $fileExt);
                break;

            case IMAGETYPE_GIF:
                $resourceType = imagecreatefromgif($fileName); 
                $imageLayer = static::resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight,$new_width,$new_height);
                imagegif($imageLayer,$uploadPath.$imgname.$resizeFileName.'.'. $fileExt);
                break;

            case IMAGETYPE_PNG:
                $resourceType = imagecreatefrompng($fileName); 
                $imageLayer = static::resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight,$new_width,$new_height);
                imagepng($imageLayer,$uploadPath.$imgname.$resizeFileName.'.'. $fileExt);
                break;

            case IMAGETYPE_JPG:
                $resourceType = imagecreatefrompng($fileName); 
                $imageLayer = static::resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight,$new_width,$new_height);
                imagepng($imageLayer,$uploadPath.$imgname.$resizeFileName.'.'. $fileExt);
                break;

            default:
                $imageProcess = 0;
                break;
        }
        move_uploaded_file($fileName, $uploadPath. $resizeFileName. ".". $fileExt);
        return $imageName = $imgname.$resizeFileName.'.'. $fileExt;
    }

	

}