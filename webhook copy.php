<?php

$connection = mysqli_connect('localhost', 'safer', 'UeQAudjQAPEi75zj6GsX', 'safer');
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
$rawData = file_get_contents("php://input");

$responseData = json_decode($rawData, true);

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
