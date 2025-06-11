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

    private function getHttpClient()
    {
        return Http::withOptions([
            'verify' => false,
            'curl' => [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ]
        ]);
    }

    public function createBill(Payment $payment): array
    {
        $billExternalRef = 'QUIZ_' . $payment->id . '_' . time();
        
        // Get bill name based on plan type
        $billName = $this->getBillName($payment->plan_type);
        $billDescription = $this->getBillDescription($payment->plan_type);
        
        // Make sure all required fields are properly set
        $billData = [
            'userSecretKey' => $this->apiKey,
            'categoryCode' => $this->categoryCode,
            'billName' => $billName,
            'billDescription' => $billDescription,
            'billPriceSetting' => 1,
            'billPayorInfo' => 1,
            'billAmount' => number_format($payment->amount * 100, 0, '', ''), // Convert to cents
            'billReturnUrl' => route('payment.return'),
            'billCallbackUrl' => route('payment.callback'),
            'billExternalReferenceNo' => $billExternalRef,
            'billTo' => $payment->user->name,
            'billEmail' => $payment->user->email,
            'billPhone' => '+601234567890',  // Malaysian format with country code
            'billSplitPayment' => 0,
            'billSplitPaymentArgs' => '',
            'billPaymentChannel' => '0',
            'billContentEmail' => $this->getEmailContent($payment->plan_type),
            'billChargeToCustomer' => 1,
        ];

        Log::info('Creating Toyyibpay bill', [
            'payment_id' => $payment->id,
            'bill_data' => array_merge($billData, ['userSecretKey' => '[HIDDEN]']),
            'api_url' => $this->baseUrl . '/index.php/api/createBill'
        ]);

        try {
            $response = $this->getHttpClient()
                ->timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ])
                ->asForm()
                ->post($this->baseUrl . '/index.php/api/createBill', $billData);
            
            Log::info('Toyyibpay API response', [
                'payment_id' => $payment->id,
                'status_code' => $response->status(),
                'response_body' => $response->body(),
                'response_headers' => $response->headers()
            ]);
            
            if ($response->successful()) {
                $responseData = $response->json();
                
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
                        'message' => 'Unexpected response format from payment gateway: ' . json_encode($responseData),
                    ];
                }
                
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
                    'payment_url' => $paymentUrl,
                    'plan_type' => $payment->plan_type,
                    'amount' => $payment->amount
                ]);

                return [
                    'success' => true,
                    'bill_code' => $billInfo['BillCode'],
                    'payment_url' => $paymentUrl,
                    'external_ref' => $billExternalRef,
                ];
            } else {
                $errorBody = $response->body();
                Log::error('Toyyibpay API request failed', [
                    'payment_id' => $payment->id,
                    'status' => $response->status(),
                    'response' => $errorBody,
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Payment service returned error: ' . $errorBody,
                    'status_code' => $response->status(),
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

    private function getBillName(string $planType): string
    {
        return match($planType) {
            'monthly' => 'Quiz Premium Monthly',
            'yearly' => 'Quiz Premium Yearly',
            default => 'Quiz Premium'
        };
    }

    private function getBillDescription(string $planType): string
    {
        return match($planType) {
            'monthly' => 'Monthly premium subscription for unlimited quiz & flashcard generation',
            'yearly' => 'Yearly premium subscription for unlimited quiz & flashcard generation (12 months)',
            default => 'Premium subscription for unlimited features'
        };
    }

    private function getEmailContent(string $planType): string
    {
        return match($planType) {
            'monthly' => 'Thank you for subscribing to our monthly premium plan! Enjoy unlimited features.',
            'yearly' => 'Thank you for subscribing to our yearly premium plan! Enjoy 12 months of unlimited features.',
            default => 'Thank you for upgrading to premium!'
        };
    }

    public function getBillTransactions(string $billCode): array
    {
        try {
            $response = $this->getHttpClient()
                ->timeout(30)
                ->asForm()
                ->post($this->baseUrl . '/index.php/api/getBillTransactions', [
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

        if ($status == '1') {
            if ($payment->isPending()) {
                $payment->markAsSuccess($callbackData);
                
                Log::info('Subscription payment completed successfully via callback', [
                    'payment_id' => $payment->id,
                    'user_id' => $payment->user_id,
                    'amount' => $amount,
                    'plan_type' => $payment->plan_type,
                    'subscription_expires_at' => $payment->subscription_expires_at
                ]);
            }
            
            return [
                'success' => true,
                'payment' => $payment,
                'message' => 'Subscription payment completed successfully',
            ];
        } else {
            if ($payment->isPending()) {
                $payment->markAsFailed($callbackData);
                
                Log::info('Subscription payment failed via callback', [
                    'payment_id' => $payment->id,
                    'user_id' => $payment->user_id,
                    'status' => $status,
                    'plan_type' => $payment->plan_type
                ]);
            }
            
            return [
                'success' => false,
                'payment' => $payment,
                'message' => 'Subscription payment failed or was cancelled',
            ];
        }
    }

    public function testConnection(): array
    {
        try {
            $testData = [
                'userSecretKey' => $this->apiKey,
                'categoryCode' => $this->categoryCode,
            ];

            $response = $this->getHttpClient()
                ->timeout(10)
                ->asForm()
                ->post($this->baseUrl . '/index.php/api/getCategoryDetails', $testData);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Connection successful',
                    'response' => $response->json(),
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Connection failed',
                    'status' => $response->status(),
                    'response' => $response->body(),
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage(),
            ];
        }
    }
}