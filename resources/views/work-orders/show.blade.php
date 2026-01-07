@extends('layouts.app')

@section('title', "Work Order #{$workOrder->id} - TPAS Work Form")

@push('styles')
<style>
    .upload-zone {
        border: 2px dashed #d1d5db;
        transition: all 0.3s ease;
    }
    .upload-zone:hover, .upload-zone.drag-over {
        border-color: #ff8563;
        background-color: #fff5f2;
    }
    .upload-zone.drag-over .upload-icon {
        transform: scale(1.1);
        color: #ff8563;
    }
    .category-btn {
        transition: all 0.2s ease;
    }
    .category-btn.active {
        background-color: #ff8563;
        color: white;
        border-color: #ff8563;
    }
    .photo-card {
        animation: fadeIn 0.3s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }
    .photo-card.uploading .photo-overlay {
        display: flex;
    }
    .progress-bar {
        transition: width 0.3s ease;
    }
    .upload-icon {
        transition: all 0.3s ease;
    }
    .empty-state {
        background: repeating-linear-gradient(
            45deg,
            #f9fafb,
            #f9fafb 10px,
            #f3f4f6 10px,
            #f3f4f6 20px
        );
    }
</style>
@endpush

@section('content')
<div class="mb-4 flex items-center justify-between">
    <a href="{{ route('track.index') }}" class="text-primary hover:underline flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Kembali
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
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Location & Activity Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between cursor-pointer" onclick="toggleSection('info')">
                <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informasi Lokasi & Aktivitas
                </h2>
                <svg id="info-chevron" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>
            <div id="info-content" class="p-4 space-y-3">
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

        <!-- Photo Upload Section -->
        @if($workOrder->status === 'Daily')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Upload Dokumentasi Foto
                </h2>
                <p class="text-sm text-gray-500 mt-1">Upload minimal 1 foto untuk setiap kategori wajib</p>
            </div>

            <!-- Category Tabs -->
            <div class="p-4 border-b border-gray-100 overflow-x-auto">
                <div class="flex gap-2 min-w-max">
                    <button onclick="selectCategory('on_site')" class="category-btn px-4 py-2 rounded-xl border-2 border-gray-200 font-medium text-sm flex items-center gap-2" data-category="on_site">
                        <span class="w-2 h-2 bg-primary rounded-full"></span>
                        On Site
                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full" id="count-on_site">{{ $photosByCategory['on_site']->count() }}</span>
                    </button>
                    <button onclick="selectCategory('work_area')" class="category-btn px-4 py-2 rounded-xl border-2 border-gray-200 font-medium text-sm flex items-center gap-2" data-category="work_area">
                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                        Area Pekerjaan
                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full" id="count-work_area">{{ $photosByCategory['work_area']->count() }}</span>
                    </button>
                    <button onclick="selectCategory('work_proof')" class="category-btn px-4 py-2 rounded-xl border-2 border-gray-200 font-medium text-sm flex items-center gap-2" data-category="work_proof">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        Bukti Pekerjaan
                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full" id="count-work_proof">{{ $photosByCategory['work_proof']->count() }}</span>
                    </button>
                    <button onclick="selectCategory('other')" class="category-btn px-4 py-2 rounded-xl border-2 border-gray-200 font-medium text-sm flex items-center gap-2" data-category="other">
                        <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                        Lainnya
                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full" id="count-other">{{ $photosByCategory['other']->count() }}</span>
                    </button>
                </div>
            </div>

            <!-- Upload Zone -->
            <div class="p-4">
                <div id="uploadZone" class="upload-zone rounded-2xl p-8 text-center cursor-pointer"
                     onclick="document.getElementById('fileInput').click()"
                     ondragover="handleDragOver(event)"
                     ondragleave="handleDragLeave(event)"
                     ondrop="handleDrop(event)">
                    <input type="file" id="fileInput" accept="image/*" multiple capture="environment" class="hidden" onchange="handleFileSelect(event)">
                    <svg class="upload-icon w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <p class="text-gray-700 font-medium">Klik atau drag & drop foto di sini</p>
                    <p class="text-sm text-gray-500 mt-1">Maksimal 10MB per foto. Format: JPG, PNG, WebP</p>
                    <p class="text-xs text-gray-400 mt-2">Foto akan dikompres otomatis (max 1MB)</p>
                </div>

                <!-- Upload Progress -->
                <div id="uploadProgress" class="hidden mt-4">
                    <div class="flex items-center justify-between text-sm mb-2">
                        <span class="text-gray-600">Mengupload...</span>
                        <span id="progressText" class="text-gray-500">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div id="progressBar" class="progress-bar bg-primary h-2 rounded-full" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- Photo Grid -->
            <div class="p-4 pt-0">
                <div id="photoGrid" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    <!-- Photos will be loaded here -->
                </div>
                <div id="emptyState" class="hidden empty-state rounded-xl p-8 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-gray-500">Belum ada foto di kategori ini</p>
                    <p class="text-sm text-gray-400 mt-1">Upload foto untuk memulai</p>
                </div>
            </div>
        </div>
        @else
        <!-- Read-only photo view for Final status -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Dokumentasi Foto
                </h2>
            </div>

            <!-- Category Tabs -->
            <div class="p-4 border-b border-gray-100 overflow-x-auto">
                <div class="flex gap-2 min-w-max">
                    <button onclick="selectCategory('on_site')" class="category-btn px-4 py-2 rounded-xl border-2 border-gray-200 font-medium text-sm flex items-center gap-2" data-category="on_site">
                        <span class="w-2 h-2 bg-primary rounded-full"></span>
                        On Site
                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full" id="count-on_site">{{ $photosByCategory['on_site']->count() }}</span>
                    </button>
                    <button onclick="selectCategory('work_area')" class="category-btn px-4 py-2 rounded-xl border-2 border-gray-200 font-medium text-sm flex items-center gap-2" data-category="work_area">
                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                        Area Pekerjaan
                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full" id="count-work_area">{{ $photosByCategory['work_area']->count() }}</span>
                    </button>
                    <button onclick="selectCategory('work_proof')" class="category-btn px-4 py-2 rounded-xl border-2 border-gray-200 font-medium text-sm flex items-center gap-2" data-category="work_proof">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        Bukti Pekerjaan
                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full" id="count-work_proof">{{ $photosByCategory['work_proof']->count() }}</span>
                    </button>
                    @if($photosByCategory['other']->isNotEmpty())
                    <button onclick="selectCategory('other')" class="category-btn px-4 py-2 rounded-xl border-2 border-gray-200 font-medium text-sm flex items-center gap-2" data-category="other">
                        <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                        Lainnya
                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full" id="count-other">{{ $photosByCategory['other']->count() }}</span>
                    </button>
                    @endif
                </div>
            </div>

            <!-- Photo Grid (Read-only) -->
            <div class="p-4">
                <div id="photoGrid" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    <!-- Photos will be loaded here -->
                </div>
            </div>
        </div>
        @endif

        <!-- BAST Document (if Final) -->
        @if($workOrder->status === 'Final' && $workOrder->bast_scan_path)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                    </svg>
                    Berita Acara Serah Terima
                </h2>
            </div>
            <div class="p-4">
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="font-medium text-gray-900">BAST Document</div>
                        <div class="text-sm text-gray-500">Dokumen serah terima tersimpan</div>
                    </div>
                    <a href="{{ asset('storage/' . $workOrder->bast_scan_path) }}" target="_blank" class="px-4 py-2 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition text-sm font-medium">
                        Lihat
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Actions Sidebar -->
    <div class="space-y-4">
        @if($workOrder->status === 'Daily')
            <!-- Edit Button -->
            <a href="{{ route('work-orders.edit', $workOrder->id) }}" class="block w-full text-center px-4 py-3 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition font-medium flex items-center justify-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Work Order
            </a>

            <!-- Evidence Status -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <h3 class="font-semibold text-gray-900 mb-3">Status Dokumentasi</h3>
                <div class="space-y-2">
                    <div class="flex items-center justify-between py-2">
                        <span class="text-sm text-gray-600 flex items-center gap-2">
                            <span class="w-2 h-2 {{ $photosByCategory['on_site']->count() > 0 ? 'bg-green-500' : 'bg-red-400' }} rounded-full"></span>
                            On Site
                        </span>
                        <span class="text-sm {{ $photosByCategory['on_site']->count() > 0 ? 'text-green-600' : 'text-red-500' }}">
                            {{ $photosByCategory['on_site']->count() > 0 ? '✓' : '✗' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-sm text-gray-600 flex items-center gap-2">
                            <span class="w-2 h-2 {{ $photosByCategory['work_area']->count() > 0 ? 'bg-green-500' : 'bg-red-400' }} rounded-full"></span>
                            Area Pekerjaan
                        </span>
                        <span class="text-sm {{ $photosByCategory['work_area']->count() > 0 ? 'text-green-600' : 'text-red-500' }}">
                            {{ $photosByCategory['work_area']->count() > 0 ? '✓' : '✗' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-sm text-gray-600 flex items-center gap-2">
                            <span class="w-2 h-2 {{ $photosByCategory['work_proof']->count() > 0 ? 'bg-green-500' : 'bg-red-400' }} rounded-full"></span>
                            Bukti Pekerjaan
                        </span>
                        <span class="text-sm {{ $photosByCategory['work_proof']->count() > 0 ? 'text-green-600' : 'text-red-500' }}">
                            {{ $photosByCategory['work_proof']->count() > 0 ? '✓' : '✗' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Submit Daily Report -->
            <form method="POST" action="{{ route('work-orders.submit-daily', $workOrder->id) }}" onsubmit="return confirm('Submit Daily Report? Pastikan semua foto dokumentasi sudah diupload.')">
                @csrf
                <button type="submit" class="w-full px-4 py-3 bg-yellow-500 hover:bg-yellow-600 text-white rounded-xl transition font-medium flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Submit Daily Report
                </button>
            </form>

            <!-- Evidence Warning for Final Report -->
            @if(!$workOrder->hasRequiredEvidence())
            <div class="bg-orange-50 border border-orange-200 rounded-xl p-3 mb-3">
                <p class="text-xs text-orange-700 text-center">
                    ⚠ Upload foto untuk semua kategori (On Site, Area Pekerjaan, Bukti Pekerjaan) sebelum submit Final Report
                </p>
            </div>
            @endif

            <!-- Submit Final Report -->
            <a href="{{ route('work-orders.final-form', $workOrder->id) }}" class="block w-full text-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl transition font-medium flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Submit Final Report
            </a>
        @endif

        @if($workOrder->status === 'Final')
            <!-- Download PDF -->
            <a href="{{ route('reports.download', $workOrder->id) }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-primary hover:bg-primaryDark text-white rounded-xl transition font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download PDF
            </a>

            <!-- Completed Badge -->
            <div class="bg-green-50 border border-green-200 rounded-2xl p-4 text-center">
                <svg class="w-12 h-12 mx-auto text-green-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="font-semibold text-green-700">Work Order Selesai</p>
                <p class="text-sm text-green-600">Status: Final Report</p>
            </div>
        @endif
    </div>
</div>

<script>
    const workOrderId = {{ $workOrder->id }};
    const uploadUrl = "{{ route('work-orders.upload-evidence', $workOrder->id) }}";
    const deleteUrlPrefix = "{{ url('work-orders/evidence') }}";
    const isDaily = {{ $workOrder->status === 'Daily' ? 'true' : 'false' }};

    // Initial photos data
    const photosByCategory = {
        on_site: @js($photosByCategory['on_site']),
        work_area: @js($photosByCategory['work_area']),
        work_proof: @js($photosByCategory['work_proof']),
        other: @js($photosByCategory['other'])
    };

    let currentCategory = 'on_site';

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        selectCategory('on_site');
    });

    function toggleSection(section) {
        const content = document.getElementById(section + '-content');
        const chevron = document.getElementById(section + '-chevron');
        content.classList.toggle('hidden');
        chevron.classList.toggle('rotate-180');
    }

    function selectCategory(category) {
        currentCategory = category;

        // Update button states
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.category === category) {
                btn.classList.add('active');
            }
        });

        renderPhotos();
    }

    function renderPhotos() {
        const grid = document.getElementById('photoGrid');
        const emptyState = document.getElementById('emptyState');
        const photos = photosByCategory[currentCategory];

        grid.innerHTML = '';

        if (photos.length === 0) {
            emptyState.classList.remove('hidden');
        } else {
            emptyState.classList.add('hidden');
            photos.forEach(photo => {
                grid.innerHTML += createPhotoCard(photo);
            });
        }
    }

    function createPhotoCard(photo) {
        const canDelete = isDaily;
        const deleteBtn = canDelete ? `
            <button onclick="deletePhoto(${photo.id})" class="absolute top-2 right-2 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        ` : '';

        return `
            <div class="photo-card group relative rounded-xl overflow-hidden shadow-sm" data-id="${photo.id}">
                <img src="${photo.url}" class="w-full h-32 object-cover" alt="Evidence photo">
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-2">
                    <p class="text-white text-xs">${photo.size || ''}</p>
                </div>
                ${deleteBtn}
            </div>
        `;
    }

    // Drag and drop handlers
    function handleDragOver(e) {
        e.preventDefault();
        e.stopPropagation();
        document.getElementById('uploadZone').classList.add('drag-over');
    }

    function handleDragLeave(e) {
        e.preventDefault();
        e.stopPropagation();
        document.getElementById('uploadZone').classList.remove('drag-over');
    }

    function handleDrop(e) {
        e.preventDefault();
        e.stopPropagation();
        document.getElementById('uploadZone').classList.remove('drag-over');

        const files = e.dataTransfer.files;
        handleFiles(files);
    }

    function handleFileSelect(e) {
        const files = e.target.files;
        handleFiles(files);
        e.target.value = ''; // Reset input
    }

    async function handleFiles(files) {
        for (let file of files) {
            if (file.type.startsWith('image/')) {
                await uploadFile(file);
            }
        }
    }

    async function uploadFile(file) {
        const formData = new FormData();
        formData.append('photo', file);
        formData.append('category', currentCategory);

        // Show progress
        const progressDiv = document.getElementById('uploadProgress');
        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');
        progressDiv.classList.remove('hidden');

        try {
            const response = await fetch(uploadUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            // Simulate progress
            let progress = 0;
            const progressInterval = setInterval(() => {
                if (progress < 90) {
                    progress += 10;
                    progressBar.style.width = progress + '%';
                    progressText.textContent = progress + '%';
                }
            }, 100);

            if (response.ok) {
                clearInterval(progressInterval);
                progressBar.style.width = '100%';
                progressText.textContent = '100%';

                const result = await response.json();

                if (result.success) {
                    // Add photo to collection
                    photosByCategory[currentCategory].push(result.photo);
                    updateCount(currentCategory);
                    renderPhotos();

                    // Show success message
                    showToast(result.message, 'success');
                }
            } else {
                clearInterval(progressInterval);
                const error = await response.json();
                showToast(error.message || 'Gagal upload foto', 'error');
            }
        } catch (error) {
            showToast('Terjadi kesalahan saat upload', 'error');
        }

        // Hide progress after delay
        setTimeout(() => {
            progressDiv.classList.add('hidden');
            progressBar.style.width = '0%';
            progressText.textContent = '0%';
        }, 1000);
    }

    async function deletePhoto(photoId) {
        if (!confirm('Hapus foto ini?')) return;

        try {
            const response = await fetch(`${deleteUrlPrefix}/${photoId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const result = await response.json();

            if (result.success) {
                // Remove from collection
                photosByCategory[currentCategory] = photosByCategory[currentCategory].filter(p => p.id !== photoId);
                updateCount(currentCategory);
                renderPhotos();
                showToast(result.message, 'success');
            } else {
                showToast(result.message, 'error');
            }
        } catch (error) {
            showToast('Terjadi kesalahan', 'error');
        }
    }

    function updateCount(category) {
        const countEl = document.getElementById('count-' + category);
        if (countEl) {
            countEl.textContent = photosByCategory[category].length;
        }
    }

    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
        toast.className = `fixed bottom-4 right-4 ${bgColor} text-white px-6 py-3 rounded-xl shadow-lg z-50 transform transition-all duration-300`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-2');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>
@endsection
