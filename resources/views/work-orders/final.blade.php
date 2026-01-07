@extends('layouts.app')

@section('title', "Final Report - Work Order #{$workOrder->id}")

@section('content')
<div class="mb-4">
    <a href="{{ route('work-orders.show', $workOrder->id) }}" class="text-primary hover:underline flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Kembali
    </a>
</div>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Submit Final Report</h1>
    <p class="text-gray-600">Lengkapi informasi untuk menyelesaikan work order ini</p>
</div>

<div class="max-w-xl">
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6 flex gap-3">
        <svg class="w-6 h-6 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
        <div class="text-sm text-yellow-800">
            <strong>Penting:</strong> Final Report tidak dapat diedit setelah disubmit. Pastikan semua data sudah benar.
        </div>
    </div>

    @error('evidence')
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 flex gap-3">
        <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div class="text-sm text-red-800">{{ $message }}</div>
    </div>
    @enderror

    <form method="POST" action="{{ route('work-orders.submit-final', $workOrder->id) }}" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-gray-100 divide-y divide-gray-100">
        @csrf

        <!-- Jam Selesai -->
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Jam Selesai Pekerjaan
            </h2>

            <div>
                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai <span class="text-red-500">*</span></label>
                <input type="time" id="end_time" name="end_time" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                    value="{{ old('end_time') }}">
                @error('end_time') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- BAST Upload -->
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Upload BAST
            </h2>

            <div>
                <label for="bast_scan" class="block text-sm font-medium text-gray-700 mb-1">Scan BAST <span class="text-red-500">*</span></label>
                <input type="file" id="bast_scan" name="bast_scan" accept=".pdf,.jpg,.jpeg,.png" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                <p class="mt-1 text-xs text-gray-500">Format: PDF, JPG, JPEG, PNG. Maksimal 10MB</p>
                @error('bast_scan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Catatan Final -->
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Catatan Final (Opsional)
            </h2>

            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan</label>
                <textarea id="notes" name="notes" rows="3"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none resize-none"
                    placeholder="Catatan penutup...">{{ old('notes', $workOrder->notes) }}</textarea>
                @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Actions -->
        <div class="p-6 bg-gray-50 rounded-b-2xl flex gap-3">
            <a href="{{ route('work-orders.show', $workOrder->id) }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-100 transition font-medium">
                Batal
            </a>
            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin submit Final Report? Setelah disubmit, data tidak dapat diubah.')" class="flex-1 bg-green-600 hover:bg-green-700 text-white rounded-xl transition font-medium py-2.5">
                Submit Final Report
            </button>
        </div>
    </form>

    <!-- Evidence Check -->
    <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <h3 class="font-semibold text-gray-900 mb-3">Status Dokumentasi</h3>
        <div class="space-y-2">
            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                <span class="text-sm text-gray-600">On Site</span>
                <span class="text-sm {{ $workOrder->evidencePhotos->where('category', 'on_site')->count() > 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $workOrder->evidencePhotos->where('category', 'on_site')->count() > 0 ? '✓ Terupload' : '✗ Belum diupload' }}
                </span>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                <span class="text-sm text-gray-600">Area Pekerjaan</span>
                <span class="text-sm {{ $workOrder->evidencePhotos->where('category', 'work_area')->count() > 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $workOrder->evidencePhotos->where('category', 'work_area')->count() > 0 ? '✓ Terupload' : '✗ Belum diupload' }}
                </span>
            </div>
            <div class="flex items-center justify-between py-2">
                <span class="text-sm text-gray-600">Bukti Pekerjaan</span>
                <span class="text-sm {{ $workOrder->evidencePhotos->where('category', 'work_proof')->count() > 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $workOrder->evidencePhotos->where('category', 'work_proof')->count() > 0 ? '✓ Terupload' : '✗ Belum diupload' }}
                </span>
            </div>
        </div>

        @if(!$workOrder->hasRequiredEvidence())
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-sm text-red-600 mb-2">⚠ Semua kategori dokumentasi wajib diupload sebelum submit Final Report.</p>
            <a href="{{ route('work-orders.show', $workOrder->id) }}" class="text-sm text-primary hover:underline font-medium">
                ← Kembali untuk upload foto
            </a>
        </div>
        @else
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-sm text-green-600">✓ Semua dokumentasi lengkap. Siap untuk submit Final Report.</p>
        </div>
        @endif
    </div>
</div>
@endsection
