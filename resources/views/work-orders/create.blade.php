@extends('layouts.app')

@section('title', 'Input Work Order - TPAS Work Form')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Input Work Order</h1>
    <p class="text-gray-600">Isi formulir di bawah untuk membuat laporan kerja baru</p>
</div>

<div class="max-w-2xl">
    <form method="POST" action="{{ route('work-orders.store') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 divide-y divide-gray-100">
        @csrf

        <!-- Section: Lokasi -->
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Lokasi Pekerjaan
            </h2>

            <div class="space-y-4">
                <div>
                    <label for="location_city" class="block text-sm font-medium text-gray-700 mb-1">Kota <span class="text-red-500">*</span></label>
                    <input type="text" id="location_city" name="location_city" value="{{ old('location_city') }}" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                        placeholder="Contoh: Jakarta Selatan">
                    @error('location_city') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="location_district" class="block text-sm font-medium text-gray-700 mb-1">Kecamatan <span class="text-red-500">*</span></label>
                    <input type="text" id="location_district" name="location_district" value="{{ old('location_district') }}" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                        placeholder="Contoh: Pasar Minggu">
                    @error('location_district') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="location_village" class="block text-sm font-medium text-gray-700 mb-1">Desa/Kelurahan <span class="text-red-500">*</span></label>
                    <input type="text" id="location_village" name="location_village" value="{{ old('location_village') }}" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                        placeholder="Contoh: Kebagusan">
                    @error('location_village') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Section: Waktu -->
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Waktu Pelaksanaan
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="work_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kerja <span class="text-red-500">*</span></label>
                    <input type="date" id="work_date" name="work_date" value="{{ old('work_date') }}" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    @error('work_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai <span class="text-red-500">*</span></label>
                    <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    @error('start_time') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Section: Detail Aktivitas -->
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Detail Aktivitas
            </h2>

            <div class="space-y-4">
                <div>
                    <label for="activity_details" class="block text-sm font-medium text-gray-700 mb-1">Jenis Aktivitas <span class="text-red-500">*</span></label>
                    <textarea id="activity_details" name="activity_details" rows="4" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none resize-none"
                        placeholder="Deskripsikan jenis pekerjaan yang dilakukan...">{{ old('activity_details') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Minimal 10 karakter</p>
                    @error('activity_details') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="site_pic" class="block text-sm font-medium text-gray-700 mb-1">Site PIC <span class="text-red-500">*</span></label>
                    <input type="text" id="site_pic" name="site_pic" value="{{ old('site_pic') }}" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                        placeholder="Nama Person in Charge di lokasi">
                    @error('site_pic') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <textarea id="notes" name="notes" rows="2"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none resize-none"
                        placeholder="Catatan tambahan (opsional)...">{{ old('notes') }}</textarea>
                    @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="p-6 bg-gray-50 rounded-b-2xl flex gap-3">
            <a href="{{ route('dashboard') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-100 transition font-medium">
                Batal
            </a>
            <button type="submit" class="flex-1 bg-primary hover:bg-primaryDark text-white rounded-xl transition font-medium py-2.5">
                Simpan Draft
            </button>
        </div>
    </form>
</div>
@endsection
