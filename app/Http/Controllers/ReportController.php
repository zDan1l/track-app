<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        $workOrders = WorkOrder::where('user_id', Auth::id())
            ->where('status', 'Final')
            ->latest()
            ->get();

        return view('reports.index', compact('workOrders'));
    }

    public function preview($id)
    {
        $workOrder = WorkOrder::with(['user', 'evidencePhotos'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('reports.preview', compact('workOrder'));
    }

    public function download($id)
    {
        $workOrder = WorkOrder::with(['user', 'evidencePhotos'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $pdf = PDF::loadView('reports.pdf', compact('workOrder'))
            ->setPaper('a4')
            ->setOption(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        $filename = "work-order-{$workOrder->id}-" . now()->format('YmdHis') . '.pdf';

        return $pdf->download($filename);
    }
}
