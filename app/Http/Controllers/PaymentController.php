<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\ToyyibpayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    private ToyyibpayService $toyyibpayService;

    public function __construct(ToyyibpayService $toyyibpayService)
    {
        $this->toyyibpayService = $toyyibpayService;
    }

    public function initiate(Request $request)
    {
        $user = auth()->user();

        // Check if user is already premium
        if ($user->isPremium()) {
            return redirect()->route('dashboard')
                ->with('info', 'You already have Premium access!');
        }

        // Check for existing pending payment
        $existingPayment = Payment::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('created_at', '>=', now()->subHours(24))
            ->first();

        if ($existingPayment && $existingPayment->payment_url) {
            return redirect()->route('payment.show', $existingPayment->id);
        }

        try {
            DB::beginTransaction();

            // Create payment record
            $payment = Payment::create([
                'user_id' => $user->id,
                'amount' => 5.00, // RM5
                'payment_ref' => 'PAY_' . strtoupper(Str::random(10)),
                'status' => 'pending',
            ]);

            // Create bill with Toyyibpay
            $billResult = $this->toyyibpayService->createBill($payment);

            if ($billResult['success']) {
                DB::commit();
                
                return redirect()->route('payment.show', $payment->id)
                    ->with('success', 'Payment initiated successfully. Please complete your payment.');
            } else {
                DB::rollBack();
                
                return redirect()->route('tier.upgrade')
                    ->with('error', $billResult['message']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Payment initiation error', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('tier.upgrade')
                ->with('error', 'Unable to initiate payment. Please try again.');
        }
    }

    public function show(Payment $payment)
    {
        $user = auth()->user();

        // Ensure user can only view their own payments
        if ($payment->user_id !== $user->id) {
            abort(403, 'Unauthorized access to payment.');
        }

        return view('payment.show', compact('payment', 'user'));
    }

    public function callback(Request $request)
    {
        try {
            $callbackData = $request->all();
            
            Log::info('Payment callback received', [
                'callback_data' => $callbackData,
                'ip' => $request->ip(),
            ]);

            $result = $this->toyyibpayService->processCallback($callbackData);

            if ($result['success']) {
                return response('OK', 200);
            } else {
                return response('FAILED', 400);
            }
        } catch (\Exception $e) {
            Log::error('Payment callback error', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);
            
            return response('ERROR', 500);
        }
    }

    public function return(Request $request)
    {
        $user = auth()->user();
        $status = $request->get('status_id', $request->get('status', '0'));
        $billCode = $request->get('billcode');
        $orderRef = $request->get('order_id');

        // Find payment
        $payment = null;
        if ($billCode) {
            $payment = Payment::where('toyyibpay_bill_code', $billCode)
                ->where('user_id', $user->id)
                ->first();
        }

        if (!$payment) {
            return redirect()->route('tier.upgrade')
                ->with('error', 'Payment session not found.');
        }

        // Refresh payment status
        $this->refreshPaymentStatus($payment);

        if ($payment->isSuccess()) {
            return redirect()->route('payment.success', $payment->id);
        } else if ($payment->isFailed()) {
            return redirect()->route('payment.failed', $payment->id);
        } else {
            // Still pending
            return redirect()->route('payment.show', $payment->id)
                ->with('info', 'Payment is still being processed. Please wait a moment.');
        }
    }

    public function success(Payment $payment)
    {
        $user = auth()->user();

        // Ensure user can only view their own payments
        if ($payment->user_id !== $user->id) {
            abort(403, 'Unauthorized access to payment.');
        }

        if (!$payment->isSuccess()) {
            return redirect()->route('payment.show', $payment->id);
        }

        return view('payment.success', compact('payment', 'user'));
    }

    public function failed(Payment $payment)
    {
        $user = auth()->user();

        // Ensure user can only view their own payments
        if ($payment->user_id !== $user->id) {
            abort(403, 'Unauthorized access to payment.');
        }

        return view('payment.failed', compact('payment', 'user'));
    }

    public function cancel(Payment $payment)
    {
        $user = auth()->user();

        // Ensure user can only cancel their own payments
        if ($payment->user_id !== $user->id) {
            abort(403, 'Unauthorized access to payment.');
        }

        if ($payment->isPending()) {
            $payment->update(['status' => 'failed']);
        }

        return redirect()->route('tier.upgrade')
            ->with('info', 'Payment has been cancelled.');
    }

    public function status(Payment $payment)
    {
        $user = auth()->user();

        // Ensure user can only check their own payments
        if ($payment->user_id !== $user->id) {
            abort(403, 'Unauthorized access to payment.');
        }

        // Refresh payment status
        $this->refreshPaymentStatus($payment);

        return response()->json([
            'status' => $payment->status,
            'is_success' => $payment->isSuccess(),
            'is_pending' => $payment->isPending(),
            'is_failed' => $payment->isFailed(),
            'payment_url' => $payment->payment_url,
            'amount' => $payment->formatted_amount,
        ]);
    }

    private function refreshPaymentStatus(Payment $payment): void
    {
        if (!$payment->isPending() || !$payment->toyyibpay_bill_code) {
            return;
        }

        try {
            $transactions = $this->toyyibpayService->getBillTransactions($payment->toyyibpay_bill_code);
            
            if (!empty($transactions)) {
                $latestTransaction = end($transactions);
                
                if (isset($latestTransaction['billpaymentStatus']) && $latestTransaction['billpaymentStatus'] == '1') {
                    $payment->markAsSuccess([
                        'transaction_data' => $latestTransaction,
                        'refreshed_at' => now(),
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to refresh payment status', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function history()
    {
        $user = auth()->user();
        
        $payments = Payment::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('payment.history', compact('payments', 'user'));
    }
}