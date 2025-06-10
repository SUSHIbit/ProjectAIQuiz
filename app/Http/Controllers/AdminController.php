<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::where('role', 'user')->count();
        $freeUsers = User::where('tier', 'free')->where('role', 'user')->count();
        $premiumUsers = User::where('tier', 'premium')->where('role', 'user')->count();
        $totalRevenue = Payment::where('status', 'success')->sum('amount');
        
        // Payment statistics
        $totalPayments = Payment::count();
        $successfulPayments = Payment::where('status', 'success')->count();
        $pendingPayments = Payment::where('status', 'pending')->count();
        $failedPayments = Payment::where('status', 'failed')->count();
        
        $recentPayments = Payment::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Monthly revenue (last 12 months)
        $monthlyRevenue = Payment::where('status', 'success')
            ->where('created_at', '>=', now()->subYear())
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'freeUsers', 
            'premiumUsers',
            'totalRevenue',
            'totalPayments',
            'successfulPayments',
            'pendingPayments',
            'failedPayments',
            'recentPayments',
            'monthlyRevenue'
        ));
    }

    public function users()
    {
        $users = User::where('role', 'user')
            ->withCount(['payments as successful_payments_count' => function ($query) {
                $query->where('status', 'success');
            }])
            ->withSum(['payments as total_spent' => function ($query) {
                $query->where('status', 'success');
            }], 'amount')
            ->latest()
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    public function payments()
    {
        $payments = Payment::with('user')
            ->latest()
            ->paginate(20);

        $stats = [
            'total_payments' => Payment::count(),
            'successful_payments' => Payment::where('status', 'success')->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'failed_payments' => Payment::where('status', 'failed')->count(),
            'total_revenue' => Payment::where('status', 'success')->sum('amount'),
            'average_payment' => Payment::where('status', 'success')->avg('amount'),
        ];

        return view('admin.payments', compact('payments', 'stats'));
    }
}