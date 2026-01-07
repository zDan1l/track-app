<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackController extends Controller
{
    public function index(Request $request)
    {
        $query = WorkOrder::with(['user', 'evidencePhotos'])
            ->where('user_id', Auth::id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('date_from')) {
            $query->where('work_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('work_date', '<=', $request->date_to);
        }

        $workOrders = $query->latest()->paginate(20);

        return view('track.index', compact('workOrders'));
    }

    public function show($id)
    {
        $workOrder = WorkOrder::with(['user', 'evidencePhotos'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('track.show', compact('workOrder'));
    }
}
