<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ToyyibPayService
{
    private $categoryCode;
    private $secretKey;
    private $baseUrl = 'https://dev.toyyibpay.com';

    public function __construct()
    {
        $this->categoryCode = 'wjrd2aap';
        $this->secretKey = 'qmik8n4d-lpb8-i5rt-98lr-ye5dntxbqz3r';
    }

    public function createBill(array $orderData)
    {
        try {
            $billData = [
                'userSecretKey' => $this->secretKey,
                'categoryCode' => $this->categoryCode,
                'billName' => $orderData['bill_name'],
                'billDescription' => $orderData['bill_description'],
                'billPrice' => $orderData['bill_price'], // This is in sen (cents)
                'billAmount' => $orderData['bill_amount'], // This is in sen (cents) - required for fixed amount
                'billEmail' => $orderData['bill_email'],
                'billPhone' => $orderData['bill_phone'],
                'billPaymentChannel' => '2', // Both FPX & Credit Card
                'billContentEmail' => $orderData['bill_content_email'],
                'billCallbackUrl' => route('toyyibpay.callback'),
                'billReturnUrl' => route('order.confirmation', $orderData['order_id'] ?? 0),
                'billExternalReferenceNo' => $orderData['bill_reference_no'],
                'billTo' => $orderData['bill_to'],
                'billPayorInfo' => '1', // Optional - shows and pre-fills email/phone fields
                'billPriceSetting' => '1', // Fixed amount bill
                'billChargeToCustomer' => ''
            ];

            Log::info('ToyyibPay Request Data: ' . json_encode($billData));

            $response = Http::asForm()->post($this->baseUrl . '/index.php/api/createBill', $billData);

            Log::info('ToyyibPay Response Status: ' . $response->status());
            Log::info('ToyyibPay Response Body: ' . $response->body());

            $result = $response->json();

            if (isset($result[0]['BillCode'])) {
                return [
                    'success' => true,
                    'bill_code' => $result[0]['BillCode'],
                    'bill_url' => $this->baseUrl . '/' . $result[0]['BillCode']
                ];
            } else {
                Log::error('ToyyibPay Error: ' . json_encode($result));
                return [
                    'success' => false,
                    'message' => $result['message'] ?? 'Failed to create bill'
                ];
            }

        } catch (\Exception $e) {
            Log::error('ToyyibPay Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function verifyPayment($billCode, $billPaymentStatus)
    {
        try {
            $verificationData = [
                'userSecretKey' => $this->secretKey,
                'billCode' => $billCode
            ];

            $response = Http::asForm()->post($this->baseUrl . '/index.php/api/getBillTransactions', $verificationData);
            $result = $response->json();

            if (isset($result[0]['billPaymentStatus'])) {
                $status = $result[0]['billPaymentStatus'];

                // Check if payment is successful
                if ($status == '1' || $status == '2' || $status == '3') {
                    return [
                        'success' => true,
                        'status' => 'paid',
                        'transaction_id' => $result[0]['transactionId'] ?? null
                    ];
                } else {
                    return [
                        'success' => true,
                        'status' => 'failed',
                        'message' => 'Payment failed or cancelled'
                    ];
                }
            } else {
                Log::error('ToyyibPay Verification Error: ' . json_encode($result));
                return [
                    'success' => false,
                    'message' => 'Unable to verify payment'
                ];
            }

        } catch (\Exception $e) {
            Log::error('ToyyibPay Verification Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}