<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebhookTestController extends Controller
{
    public function testToyyibPay(Request $request)
    {
        // Log the webhook data
        \Log::info('ToyyibPay Webhook Test Received: ' . json_encode($request->all()));

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Webhook received successfully',
            'received_data' => $request->all(),
            'timestamp' => now()->toISOString()
        ]);
    }

    public function testForm()
    {
        return view('webhook-test');
    }
}