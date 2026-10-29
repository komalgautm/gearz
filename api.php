<?php

header('Content-Type: application/json');

// Get parameters from URL
$code = $_GET['code'] ?? '';
$phone = $_GET['phone'] ?? '';

if (!$code || !$phone) {
    echo json_encode(['error' => 'Missing required parameters.']);
    exit;
}

// API credentials
$apiUrl = 'https://www.s18a0l11i8m12.com:54321/2iTicket_salim_app_api/public/index.php/token';
$apiKey = '5vpg8d85hyz22ql5d7el589f16lp50ol4jei8sf1B1';
$login = 'f16lp50ol4ei8s589j';
$password = 'lp50ol4jeilp5kq8d5';

// Initialize cURL for token request
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $apiUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => http_build_query([
        'login' => $login,
        'password' => $password
    ]),
    CURLOPT_HTTPHEADER => [
        "APIKEY: $apiKey",
        'Content-Type: application/x-www-form-urlencoded'
    ],
]);

$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$error = curl_error($curl);
curl_close($curl);

if ($error || $httpCode !== 200) {
    echo json_encode(['error' => 'Failed to obtain token', 'details' => $error]);
    exit;
}

$res = json_decode($response, true);
$token = $res['token'] ?? '';

if (!$token) {
    echo json_encode(['error' => 'Invalid token received.']);
    exit;
}

// API URL with parameters
$apiUrl2 = "https://www.s18a0l11i8m12.com:54321/2iTicket_salim_app_api/public/index.php/colisHistory?numero_telephone=$phone&code_envoi=$code";

// Initialize cURL for data request
$curls = curl_init();
curl_setopt_array($curls, [
    CURLOPT_URL => $apiUrl2,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $token",
        "APIKEY: $apiKey"
    ],
]);

$responses = curl_exec($curls);
$httpCode2 = curl_getinfo($curls, CURLINFO_HTTP_CODE);
$error2 = curl_error($curls);
curl_close($curls);

if ($error2 || $httpCode2 !== 200) {
    echo json_encode(['error' => 'Failed to fetch data', 'details' => $error2]);
    exit;
}

echo $responses;
?>
