<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SpyneWebhookController extends Controller
{
    public function handle(Request $request)
    {
        try {
            // Optional: Check authorization if you added a custom header in Spyne
            $headers = getallheaders();
            if (!isset($headers['Authorization']) || $headers['Authorization'] !== 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJlbnRlcnByaXNlSWQiOiJjZTlmZGU1MmUiLCJ0ZWFtSWQiOiI1ZTE5MDkwYjNjIiwidXNlcklkIjoiMTYxMGE3MmUiLCJzZWNyZXRLZXkiOiI2NzA4ODg3YmJjY2Q0MTM3YjI1OGE2NTIyYTNiMTQwYyIsImlhdCI6MTc1MjIwOTcxNSwiZXhwIjoxODQ2ODE3NzE1fQ.gIUOSkUmi24sLf2eESMOINCYtRBj2ISF6GZGKC3shts') {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Log incoming data
            Log::info('Spyne Webhook Received', $request->all());

            $payload = $request->all();

            // Basic validation
            if (!isset($payload['dealerVinID']) || !isset($payload['mediaData'])) {
                return response()->json(['error' => 'Invalid Payload'], 400);
            }

            // Example: update your local database with media info
            $vin = $payload['dealerVinID'];
            $media = $payload['mediaData'];

            // Example: Save image QC status
            // if (isset($media['image']['qcStatus']) && $media['image']['qcStatus'] === 'qc_done') {
            //     // Example DB update logic (you should modify based on your schema)
            //     \DB::table('car_qc_status')
            //         ->updateOrInsert(
            //             ['vin' => $vin, 'type' => 'image'],
            //             ['status' => 'qc_done', 'updated_at' => now()]
            //         );
            // }

            // Example: Save spin QC and iframe if available
            if (isset($media['spin']['qcStatus']) && $media['spin']['qcStatus'] === 'qc_done') {
                \DB::table('car_qc_status')
                    ->updateOrInsert(
                        ['vin' => $vin, 'type' => 'spin'],
                        [
                            'status' => 'qc_done',
                            'iframe' => $media['spin']['spinIframe'] ?? null,
                            'thumbnail' => $media['spin']['thumbnailUrl'] ?? null,
                            'updated_at' => now()
                        ]
                    );
            }

            return response()->json(['success' => true, 'message' => 'Webhook received.'], 200);

        } catch (\Exception $e) {
            Log::error('Spyne Webhook Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Server error'], 500);
        }
    }
}
