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
        $recentPayments = Payment::with('user')
            ->where('status', 'success')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'freeUsers', 
            'premiumUsers',
            'totalRevenue',
            'recentPayments'
        ));
    }

    public function users()
    {
        $users = User::where('role', 'user')
            ->withCount('payments')
            ->latest()
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    public function payments()
    {
        $payments = Payment::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.payments', compact('payments'));
    }
}