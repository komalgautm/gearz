<?php

$connection = mysqli_connect('localhost', 'root', '', 'gearz');
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}else {
    echo "successfull";
}


// $data = '{
//   "vin": "M64QSUZG39P400000",
//   "source": "console",
//   "dealerId": "e2adca58-66bc-4d56-ad16-f73823af9ba1",
//   "dealerVinID": "e2adca58-66bc-4d56-ad16-f73823af9ba1",
//   "mediaData": {
//     "image": {
//             "aiStatus": "DONE",
//             "qcStatus": "qc_done",
//             "rejectReason": null,
//             "failReason": null,
//             "imageData": [
//                 {
//                     "angle": 270,
//                     "status": "Done",
//                     "frameNo": "1",
//                     "imageId": "img-736d959a-00b3-40cb-9c96-31eafec2e73d",
//                     "imageName": "74ccc7e1-4ad9-44b4-a9f0-a671d4807d99",
//                     "inputImage": "https://spyne-media.s3.amazonaws.com/2025-01-22/ebc8485a-eda7-432d-b20a-98a61264bf51.jpeg",
//                     "outputImage": "https://spyne-media.s3.amazonaws.com/2025-01-22/car_replace_bg_4af789dd-8667-489b-a45d-cda94be7833d.jpg",
//                     "backgroundId": "85879",
//                     "rejectReason": null,
//                     "category": "Exterior",
//                     "clientMetaData": {}
//                 },
//                 {
//                     "angle": 320,
//                     "status": "Done",
//                     "frameNo": "2",
//                     "imageId": "img-c0d1294e-cc1c-4181-9282-d13c3049c7a7",
//                     "imageName": "a36d65a4-7cb5-4600-b572-cf78961247f9",
//                     "inputImage": "https://spyne-media.s3.amazonaws.com/2025-01-22/0b22d7d4-252a-4039-b99a-53976995ce6b.jpeg",
//                     "outputImage": "https://spyne-media.s3.amazonaws.com/2025-01-22/car_replace_bg_650da078-416f-4ef4-9f5d-0865cff77137.jpg",
//                     "backgroundId": "85879",
//                     "rejectReason": null,
//                     "category": "Exterior",
//                     "clientMetaData": {}
//                 }
//             ]
//         },
//     "spin": {
//       "aiStatus": "DONE",
//       "qcStatus": "qc_done",
//       "rejectReason": null,
//       "failReason": null,
//       "irameUrl": "https://assets.spyne.ai/360?sku_id=174797042a304189ade91903c9bb909a&hotspot=false&hideConfig=true",
//       "thumbnailUrl": "https://spyne-media.s3.amazonaws.com/2025-01-20/0afba6f9-e56a-45ac-9f08-ef893ac3c8aa.jpg"
//     },
//     "video":{
//       "videoId": "vid-ce92c677d67e4772be2ab39ee28b285f",
//       "aiStatus": "DONE",
//       "qcStatus": "qc_done",
//       "rejectReason": null,
//       "failReason": null,
//       "iFrameUrl": "https://assets.spyne.ai/sku_id=f032881b823f42438862ce306c29b6f4&version=v2"
//     }
//   }
// }';
$rawData = file_get_contents("php://input");

$responseData = json_decode($rawData, true);

file_put_contents("spyne_webhook_log.txt", print_r($responseData, true), FILE_APPEND);
die;
return $responseData;

// $responseData = [
//     "status" => "SUCCESS",
//     "transactionNumber" => '011' // same id as in referenceId in response of payment request.
// ];
// echo "<pre>";
// transaction Table Sql
if (isset($responseData) && isset($responseData['transactionNumber'])) {
    $payment_status = $responseData['status'];
    $transactionId = $responseData['transactionNumber'];
    $transactionSql = "SELECT * FROM tbl_transactions WHERE transaction_Id = ? AND payment_status = ? AND deleted_at IS NULL LIMIT 1";
    $stat = 'PENDING';
    $transactionResult = $connection->prepare($transactionSql);
    $transactionResult->bind_param("ss", $transactionId, $stat);
    $transactionResult->execute();
    $transactionResultdata = $transactionResult->get_result();

    // Booking Table Sql
    if ($transactionResultdata->num_rows > 0) {
        $transactionRow = $transactionResultdata->fetch_assoc();
        // print_r($transactionRow);

        $transaction_id = $transactionRow['id'];
        $order_id = $transactionRow['order_id'];

        $bookingSql = "SELECT * FROM orders WHERE id = ?";
        $bookingResult = $connection->prepare($bookingSql);
        $bookingResult->bind_param("s", $order_id);
        $bookingResult->execute();
        $bookingResultdata = $bookingResult->get_result();

        if ($bookingResultdata->num_rows > 0) {
            $bookingRow = $bookingResultdata->fetch_assoc();

            // print_r($bookingRow);
            // print_r($latestInvoiceRow);
            // echo "</pre>";

            $orderID = $bookingRow['code'];
            $travellingDateId = $bookingRow['travellingDateId'];
            $passengerCount = json_decode($bookingRow['price'], true)['passengerCount'];

            $newRequest = [
                'order_id' => $order_id,
                'transaction_id' => $transaction_id,
                'payment_status' => $payment_status,
            ];
            if ($payment_status == 'SUCCESS' || $payment_status == 'Success') {
                $updateTransactionSql = "UPDATE tbl_transactions SET payment_status = ? WHERE transaction_Id = ?";
                $updateTransactionStmt = $connection->prepare($updateTransactionSql);
                $updateTransactionStmt->bind_param("ss", $payment_status, $transactionId);
                $updateTransactionStmt->execute();

                // ✅ Update orders.status
                $order_status = 0; // Set the new status you want
                $updateOrderSql = "UPDATE orders SET status = ? WHERE id = ?";
                $updateOrderStmt = $connection->prepare($updateOrderSql);
                $updateOrderStmt->bind_param("ss", $order_status, $order_id);
                $updateOrderStmt->execute();



                $result1 = generateEmailAndPdf($newRequest);

                // echo $result1;
            } else {
                $updateTransactionSql = "UPDATE tbl_transactions SET payment_status = ? WHERE transaction_Id = ?";
                $updateTransactionStmt = $connection->prepare($updateTransactionSql);
                $updateTransactionStmt->bind_param("ss", $payment_status, $transactionId);
                $updateTransactionStmt->execute();

                // // ✅ Update orders.status
                $order_status = 6; // Set the new status you want
                $updateOrderSql = "UPDATE orders SET status = ? WHERE id = ?";
                $updateOrderStmt = $connection->prepare($updateOrderSql);
                $updateOrderStmt->bind_param("ss", $order_status, $order_id);
                $updateOrderStmt->execute();

                $travellingDateSql = "SELECT * FROM travel_package_details WHERE id = ?";
                $travellingDateSqlResult = $connection->prepare($travellingDateSql);
                $travellingDateSqlResult->bind_param("i", $travellingDateId);
                $travellingDateSqlResult->execute();
                $travellingDateSqlResultdata = $travellingDateSqlResult->get_result();

                if ($travellingDateSqlResultdata->num_rows > 0) {
                    $travellingDateSqlRow = $travellingDateSqlResultdata->fetch_assoc();
                    $availableSeat =  $travellingDateSqlRow['availableSeat'] + $passengerCount;
                    $bookedSeat = $travellingDateSqlRow['bookedSeat'] - $passengerCount;

                    $updateTransactionSql = "UPDATE travel_package_details SET availableSeat = ? , bookedSeat = ? WHERE id = ?";
                    $updateTransactionStmt = $connection->prepare($updateTransactionSql);
                    $updateTransactionStmt->bind_param("iii", $availableSeat, $bookedSeat, $travellingDateId);
                    $updateTransactionStmt->execute();
                }
                $result1 = generateEmailAndPdf($newRequest);
            }
        } else {
            // echo "Booking data not found";
        }
    } else {
        // echo "Transaction not found";
    }
} else {
    // echo "Transaction number is empty";
}


function generateEmailAndPdf($postData)
{

    $url = "https://saferbh.com/api/send_pdf_email";
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [                  // Headers
        'Content-Type: application/json',
        'Accept: application/json'
    ]);

    // Execute cURL request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Error check
    if (curl_errno($ch)) {
        // echo 'cURL Error: ' . curl_error($ch);
    } else {
        // echo "HTTP Status Code: $httpCode\n";
        // echo $response;
    }

    // Close cURL
    curl_close($ch);
}
