@extends('layouts.app')

@section('title', "Preview Report #{$workOrder->id}")

@section('content')
<div class="mb-4 flex items-center justify-between">
    <a href="{{ route('reports.index') }}" class="text-primary hover:underline flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Kembali
    </a>
    <a href="{{ route('reports.download', $workOrder->id) }}" class="px-4 py-2 bg-primary hover:bg-primaryDark text-white rounded-xl transition font-medium flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        Download PDF
    </a>
</div>

<!-- PDF Preview -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8 pb-6 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">TPAS Work Form Report</h1>
            <p class="text-gray-500 mt-1">Generated: {{ now()->format('d M Y H:i') }}</p>
        </div>

        <!-- Location -->
        <div class="mb-6">
            <h2 class="text-sm font-semibold text-gray-500 uppercase mb-2">Lokasi Pekerjaan</h2>
            <p class="text-lg font-medium text-gray-900">{{ $workOrder->location_full }}</p>
        </div>

        <!-- Activity Details -->
        <div class="mb-6">
            <h2 class="text-sm font-semibold text-gray-500 uppercase mb-2">Detail Aktivitas</h2>
            <p class="text-gray-700">{{ $workOrder->activity_details }}</p>
            <div class="mt-2 grid grid-cols-2 gap-4">
                <div>
                    <span class="text-sm text-gray-500">Site PIC:</span>
                    <span class="font-medium">{{ $workOrder->site_pic }}</span>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="mb-6">
            <h2 class="text-sm font-semibold text-gray-500 uppercase mb-2">Waktu Pelaksanaan</h2>
            <p class="text-gray-700">{{ $workOrder->work_date->format('d M Y') }}</p>
            <p class="text-gray-700">{{ $workOrder->start_time }} - {{ $workOrder->end_time ?? '--:--' }}</p>
        </div>

        <!-- Evidence Photos -->
        <div class="mb-6">
            <h2 class="text-sm font-semibold text-gray-500 uppercase mb-3">Dokumentasi</h2>
            <div class="grid grid-cols-3 gap-3">
                @foreach($workOrder->evidencePhotos as $photo)
                    <div>
                        <img src="{{ asset('storage/' . $photo->file_path) }}" class="w-full h-32 object-cover rounded-lg">
                        <p class="text-xs text-gray-500 mt-1">{{ $photo->category_label }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- BAST -->
        @if($workOrder->status === 'Final' && $workOrder->bast_scan_path)
            <div class="mb-6">
                <h2 class="text-sm font-semibold text-gray-500 uppercase mb-3">Berita Acara Serah Terima</h2>
                <img src="{{ asset('storage/' . $workOrder->bast_scan_path) }}" class="max-w-full h-auto rounded-lg border border-gray-200">
            </div>
        @endif

        <!-- Footer -->
        <div class="mt-12 pt-6 border-t border-gray-200">
            <div class="flex justify-between">
                <div>
                    <p class="text-sm text-gray-500">Dibuat oleh:</p>
                    <p class="font-medium">{{ $workOrder->user->name }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Status:</p>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $workOrder->status === 'Final' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $workOrder->status }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
