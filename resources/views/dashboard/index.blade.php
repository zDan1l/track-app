@extends('layouts.app')

@section('title', 'Dashboard - TPAS Work Form')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
    <p class="text-gray-600">Selamat datang, {{ Auth::user()->name }}!</p>
</div>

<!-- Quick Access Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <!-- Input Work Order -->
    <a href="{{ route('work-orders.create') }}" class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 hover:border-primary/30">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-primaryLight rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900">Input Work Order</h3>
                <p class="text-sm text-gray-500">Buat laporan kerja baru</p>
            </div>
        </div>
    </a>

    <!-- Track Work Order -->
    <a href="{{ route('track.index') }}" class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 hover:border-primary/30">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900">Track Work Order</h3>
                <p class="text-sm text-gray-500">Lihat status laporan</p>
            </div>
        </div>
    </a>

    <!-- Download Reports -->
    <a href="{{ route('reports.index') }}" class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 hover:border-primary/30">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-green-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900">Download Report</h3>
                <p class="text-sm text-gray-500">Unduh laporan PDF</p>
            </div>
        </div>
    </a>
</div>

<!-- Stats -->
<div class="grid grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
        <div class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
        <div class="text-sm text-gray-500">Total Work Order</div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
        <div class="text-2xl font-bold text-yellow-600">{{ $stats['daily'] }}</div>
        <div class="text-sm text-gray-500">Daily Report</div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
        <div class="text-2xl font-bold text-green-600">{{ $stats['final'] }}</div>
        <div class="text-sm text-gray-500">Final Report</div>
    </div>
</div>

<!-- Recent Activity -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100">
    <div class="p-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-900">Aktivitas Terbaru</h2>
    </div>
    <div class="divide-y divide-gray-100">
        @forelse($recentWorkOrders as $workOrder)
            <a href="{{ route('work-orders.show', $workOrder->id) }}" class="block p-4 hover:bg-gray-50 transition">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-medium text-gray-900">{{ $workOrder->location_full }}</div>
                        <div class="text-sm text-gray-500">{{ $workOrder->work_date->format('d M Y') }} • {{ $workOrder->start_time }}</div>
                    </div>
                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $workOrder->status === 'Final' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $workOrder->status }}
                    </span>
                </div>
            </a>
        @empty
            <div class="p-8 text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p>Belum ada work order</p>
                <a href="{{ route('work-orders.create') }}" class="inline-block mt-3 text-primary font-medium hover:underline">Buat Work Order pertama</a>
            </div>
        @endforelse
    </div>
</div>
@endsection
