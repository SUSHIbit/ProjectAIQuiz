<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ToyyibpayService
{
    private string $apiKey;
    private string $categoryCode;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.toyyibpay.api_key');
        $this->categoryCode = config('services.toyyibpay.category_code');
        $this->baseUrl = config('services.toyyibpay.base_url', 'https://dev.toyyibpay.com');
        
        if (empty($this->apiKey) || empty($this->categoryCode)) {
            Log::error('Toyyibpay configuration missing', [
                'api_key_set' => !empty($this->apiKey),
                'category_code_set' => !empty($this->categoryCode),
                'base_url' => $this->baseUrl
            ]);
            throw new \Exception('Toyyibpay configuration is incomplete. Please check your .env file.');
        }
    }

    public function createBill(Payment $payment): array
    {
        $billExternalRef = 'QUIZ_' . $payment->id . '_' . time();
        
        $billData = [
            'userSecretKey' => $this->apiKey,
            'categoryCode' => $this->categoryCode,
            'billName' => 'AI Quiz Generator - Premium Upgrade',
            'billDescription' => 'Upgrade to Premium for unlimited AI generations and flashcards',
            'billPriceSetting' => 1, // Fixed price
            'billPayorInfo' => 1, // Collect payer info
            'billAmount' => number_format($payment->amount * 100, 0, '', ''), // Convert to cents, no decimals
            'billReturnUrl' => route('payment.return'),
            'billCallbackUrl' => route('payment.callback'),
            'billExternalReferenceNo' => $billExternalRef,
            'billTo' => $payment->user->name,
            'billEmail' => $payment->user->email,
            'billPhone' => '', // Optional
            'billSplitPayment' => 0,
            'billSplitPaymentArgs' => '',
            'billPaymentChannel' => '0', // All channels
            'billContentEmail' => 'Thank you for upgrading to Premium!',
            'billChargeToCustomer' => 1,
        ];

        Log::info('Creating Toyyibpay bill', [
            'payment_id' => $payment->id,
            'bill_data' => $billData,
            'api_url' => $this->baseUrl . '/index.php/api/createBill'
        ]);

        try {
            $response = Http::timeout(30)
                ->asForm() // Send as form data
                ->post($this->baseUrl . '/index.php/api/createBill', $billData);
            
            Log::info('Toyyibpay API response', [
                'payment_id' => $payment->id,
                'status_code' => $response->status(),
                'response_body' => $response->body(),
                'response_headers' => $response->headers()
            ]);
            
            if ($response->successful()) {
                $responseData = $response->json();
                
                // Handle both array and object responses
                if (is_array($responseData) && isset($responseData[0]['BillCode'])) {
                    $billInfo = $responseData[0];
                } elseif (isset($responseData['BillCode'])) {
                    $billInfo = $responseData;
                } else {
                    Log::error('Unexpected response format from Toyyibpay', [
                        'payment_id' => $payment->id,
                        'response' => $responseData,
                    ]);
                    
                    return [
                        'success' => false,
                        'message' => 'Unexpected response format from payment gateway',
                    ];
                }
                
                // Update payment with Toyyibpay details
                $payment->update([
                    'toyyibpay_bill_code' => $billInfo['BillCode'],
                    'toyyibpay_bill_external_ref' => $billExternalRef,
                    'toyyibpay_category_code' => $this->categoryCode,
                    'toyyibpay_response' => $responseData,
                ]);

                $paymentUrl = $this->baseUrl . '/' . $billInfo['BillCode'];

                Log::info('Toyyibpay bill created successfully', [
                    'payment_id' => $payment->id,
                    'bill_code' => $billInfo['BillCode'],
                    'payment_url' => $paymentUrl
                ]);

                return [
                    'success' => true,
                    'bill_code' => $billInfo['BillCode'],
                    'payment_url' => $paymentUrl,
                    'external_ref' => $billExternalRef,
                ];
            } else {
                Log::error('Toyyibpay API request failed', [
                    'payment_id' => $payment->id,
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Payment service returned error code: ' . $response->status(),
                ];
            }
        } catch (\Exception $e) {
            Log::error('Toyyibpay service error', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage(),
            ];
        }
    }

    public function getBillTransactions(string $billCode): array
    {
        try {
            $response = Http::timeout(30)->asForm()->post($this->baseUrl . '/index.php/api/getBillTransactions', [
                'billCode' => $billCode,
                'userSecretKey' => $this->apiKey,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return [];
        } catch (\Exception $e) {
            Log::error('Failed to get bill transactions', [
                'bill_code' => $billCode,
                'error' => $e->getMessage(),
            ]);
            
            return [];
        }
    }

    public function verifyCallback(array $callbackData): bool
    {
        // Basic validation - ToyyibPay callbacks might have different field names
        $requiredFields = ['billcode'];
        
        foreach ($requiredFields as $field) {
            if (!isset($callbackData[$field])) {
                Log::warning('Missing required field in callback', [
                    'field' => $field,
                    'callback_data' => $callbackData,
                ]);
                return false;
            }
        }
        
        return true;
    }

    public function processCallback(array $callbackData): array
    {
        if (!$this->verifyCallback($callbackData)) {
            return [
                'success' => false,
                'message' => 'Invalid callback data',
            ];
        }

        $billCode = $callbackData['billcode'];
        $status = $callbackData['status_id'] ?? $callbackData['status'] ?? '0';
        $amount = isset($callbackData['amount']) ? $callbackData['amount'] / 100 : 0;

        // Find payment by bill code
        $payment = Payment::where('toyyibpay_bill_code', $billCode)->first();

        if (!$payment) {
            Log::error('Payment not found for callback', [
                'bill_code' => $billCode,
                'callback_data' => $callbackData,
            ]);
            
            return [
                'success' => false,
                'message' => 'Payment record not found',
            ];
        }

        // Process based on status
        if ($status == '1') { // Success
            if ($payment->isPending()) {
                $payment->markAsSuccess($callbackData);
                
                Log::info('Payment completed successfully via callback', [
                    'payment_id' => $payment->id,
                    'user_id' => $payment->user_id,
                    'amount' => $amount,
                ]);
            }
            
            return [
                'success' => true,
                'payment' => $payment,
                'message' => 'Payment completed successfully',
            ];
        } else {
            // Failed payment
            if ($payment->isPending()) {
                $payment->markAsFailed($callbackData);
                
                Log::info('Payment failed via callback', [
                    'payment_id' => $payment->id,
                    'user_id' => $payment->user_id,
                    'status' => $status,
                ]);
            }
            
            return [
                'success' => false,
                'payment' => $payment,
                'message' => 'Payment failed or was cancelled',
            ];
        }
    }
}