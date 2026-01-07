<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total' => $user->workOrders()->count(),
            'daily' => $user->workOrders()->where('status', 'Daily')->count(),
            'final' => $user->workOrders()->where('status', 'Final')->count(),
        ];

        $recentWorkOrders = $user->workOrders()
            ->with('evidencePhotos')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.index', compact('stats', 'recentWorkOrders'));
    }
}
