<?php
$tripId = $_GET['tripId'];
$url = 'https://api.way2cloud.gocurb.com:8888/WhiskyService.svc/rest/CancelTripReservation';

// JSON Payload (Properly Encoded)
$payload = json_encode([
    "companyId" => 70,
    "tripId" => $tripId
]);

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 10, // Set a timeout for better reliability
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_HTTPHEADER => [
        'Authorization: Basic SVRPQV9BUFA6UFBBX0FPVEk=', // Ensure this key is valid
        'ConfigurationId: 9994',
        'Content-Type: application/json'
    ],
]);

$response = curl_exec($curl);
$error = curl_error($curl); // Capture any cURL errors
curl_close($curl);

// Check if cURL encountered an error
if ($error) {
    error_log("cURL Error: " . $error); // Log the error for debugging
    //return json_encode(["success" => false, "error" => $error]);
    echo $error;
}

echo $response;
