@extends('layouts.app')

@section('title', 'Reports - TPAS Work Form')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Reports</h1>
    <p class="text-gray-600">Daftar laporan Final yang siap diunduh</p>
</div>

<!-- Reports List -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100">
    @forelse($workOrders as $workOrder)
        <div class="p-4 border-b border-gray-100 last:border-b-0 hover:bg-gray-50 transition">
            <div class="flex items-center justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="font-medium text-gray-900">{{ $workOrder->location_full }}</div>
                    <div class="text-sm text-gray-500">{{ $workOrder->work_date->format('d M Y') }} • {{ $workOrder->start_time }} - {{ $workOrder->end_time }}</div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('reports.preview', $workOrder->id) }}" class="px-3 py-1.5 text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition">
                        Preview
                    </a>
                    <a href="{{ route('reports.download', $workOrder->id) }}" class="px-3 py-1.5 text-sm bg-primary text-white rounded-lg hover:bg-primaryDark transition flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-1">Belum ada report</h3>
            <p class="text-gray-500">Submit Final Report terlebih dahulu untuk mengunduh PDF</p>
        </div>
    @endforelse
</div>
@endsection
