@extends('layouts.app')

@section('title', "Work Order #{$workOrder->id} - TPAS Work Form")

@section('content')
<div class="mb-4">
    <a href="{{ route('track.index') }}" class="text-primary hover:underline flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Kembali ke Track
    </a>
</div>

<!-- Status Card -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <div class="text-sm text-gray-500">Work Order #{{ $workOrder->id }}</div>
            <div class="text-xl font-bold text-gray-900">{{ $workOrder->location_full }}</div>
            <div class="text-sm text-gray-600 mt-1">{{ $workOrder->work_date->format('d M Y') }} • {{ $workOrder->start_time }} {{ $workOrder->end_time ? '- ' . $workOrder->end_time : '' }}</div>
        </div>
        <span class="px-4 py-2 text-sm font-semibold rounded-full {{ $workOrder->status === 'Final' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
            {{ $workOrder->status }} Report
        </span>
    </div>
</div>

<!-- Details -->
<div class="grid gap-6 lg:grid-cols-3">
    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Location & Activity -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900">Informasi Lokasi & Aktivitas</h2>
            </div>
            <div class="p-4 space-y-3">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-xs text-gray-500 uppercase">Kota</div>
                        <div class="font-medium text-gray-900">{{ $workOrder->location_city }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 uppercase">Kecamatan</div>
                        <div class="font-medium text-gray-900">{{ $workOrder->location_district }}</div>
                    </div>
                    <div class="col-span-2">
                        <div class="text-xs text-gray-500 uppercase">Desa/Kelurahan</div>
                        <div class="font-medium text-gray-900">{{ $workOrder->location_village }}</div>
                    </div>
                    <div class="col-span-2">
                        <div class="text-xs text-gray-500 uppercase">Site PIC</div>
                        <div class="font-medium text-gray-900">{{ $workOrder->site_pic }}</div>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <div class="text-xs text-gray-500 uppercase mb-1">Detail Aktivitas</div>
                    <div class="text-gray-700">{{ $workOrder->activity_details }}</div>
                </div>
                @if($workOrder->notes)
                    <div class="pt-2 border-t border-gray-100">
                        <div class="text-xs text-gray-500 uppercase mb-1">Catatan</div>
                        <div class="text-gray-600 text-sm">{{ $workOrder->notes }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Evidence Photos -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900">Dokumentasi Foto</h2>
            </div>
            <div class="p-4 space-y-4">
                <!-- On Site -->
                <div>
                    <div class="text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                        <span class="w-2 h-2 bg-primary rounded-full"></span> On Site
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach($workOrder->evidencePhotos->where('category', 'on_site') as $photo)
                            <img src="{{ asset('storage/' . $photo->file_path) }}" class="w-full h-24 object-cover rounded-lg">
                        @endforeach
                        @if($workOrder->evidencePhotos->where('category', 'on_site')->isEmpty())
                            <div class="col-span-3 text-center py-4 text-sm text-gray-400 bg-gray-50 rounded-lg">Tidak ada foto</div>
                        @endif
                    </div>
                </div>

                <!-- Work Area -->
                <div>
                    <div class="text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span> Area Pekerjaan
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach($workOrder->evidencePhotos->where('category', 'work_area') as $photo)
                            <img src="{{ asset('storage/' . $photo->file_path) }}" class="w-full h-24 object-cover rounded-lg">
                        @endforeach
                        @if($workOrder->evidencePhotos->where('category', 'work_area')->isEmpty())
                            <div class="col-span-3 text-center py-4 text-sm text-gray-400 bg-gray-50 rounded-lg">Tidak ada foto</div>
                        @endif
                    </div>
                </div>

                <!-- Work Proof -->
                <div>
                    <div class="text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span> Bukti Pekerjaan
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach($workOrder->evidencePhotos->where('category', 'work_proof') as $photo)
                            <img src="{{ asset('storage/' . $photo->file_path) }}" class="w-full h-24 object-cover rounded-lg">
                        @endforeach
                        @if($workOrder->evidencePhotos->where('category', 'work_proof')->isEmpty())
                            <div class="col-span-3 text-center py-4 text-sm text-gray-400 bg-gray-50 rounded-lg">Tidak ada foto</div>
                        @endif
                    </div>
                </div>

                <!-- Other -->
                @if($workOrder->evidencePhotos->where('category', 'other')->isNotEmpty())
                    <div>
                        <div class="text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                            <span class="w-2 h-2 bg-gray-400 rounded-full"></span> Dokumentasi Lain
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($workOrder->evidencePhotos->where('category', 'other') as $photo)
                                <img src="{{ asset('storage/' . $photo->file_path) }}" class="w-full h-24 object-cover rounded-lg">
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- BAST (if Final) -->
        @if($workOrder->status === 'Final' && $workOrder->bast_scan_path)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="p-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-900">Berita Acara Serah Terima</h2>
                </div>
                <div class="p-4">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <svg class="w-10 h-10 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <div class="font-medium text-gray-900">BAST Document</div>
                            <div class="text-sm text-gray-500">Dokumen serah terima</div>
                        </div>
                        <a href="{{ asset('storage/' . $workOrder->bast_scan_path) }}" target="_blank" class="ml-auto text-sm text-primary hover:underline">Lihat</a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Actions Sidebar -->
    <div class="space-y-4">
        <a href="{{ route('work-orders.show', $workOrder->id) }}" class="block w-full text-center px-4 py-3 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition font-medium">
            Lihat Detail Lengkap
        </a>

        @if($workOrder->status === 'Final')
            <!-- Download PDF -->
            <a href="{{ route('reports.download', $workOrder->id) }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-primary hover:bg-primaryDark text-white rounded-xl transition font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download PDF
            </a>
        @endif
    </div>
</div>
@endsection
