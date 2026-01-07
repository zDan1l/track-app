@extends('layouts.app')

@section('title', 'Track Work Order - TPAS Work Form')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Track Work Order</h1>
    <p class="text-gray-600">Pantau status semua work order Anda</p>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" action="{{ route('track.index') }}" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari lokasi..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none">
            </div>

            <!-- Status Filter -->
            <div>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    <option value="">Semua Status</option>
                    <option value="Daily" {{ request('status') === 'Daily' ? 'selected' : '' }}>Daily</option>
                    <option value="Final" {{ request('status') === 'Final' ? 'selected' : '' }}>Final</option>
                </select>
            </div>

            <!-- Date From -->
            <div>
                <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="Dari tanggal"
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none">
            </div>

            <!-- Date To -->
            <div class="md:col-span-4">
                <div class="flex flex-wrap gap-3">
                    <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="Sampai tanggal"
                        class="flex-1 min-w-[150px] px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    <button type="submit" class="px-6 py-2 bg-primary hover:bg-primaryDark text-white rounded-xl transition font-medium">
                        Terapkan Filter
                    </button>
                    <a href="{{ route('track.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition font-medium">
                        Reset
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Work Order List -->
<div class="space-y-3">
    @forelse($workOrders as $workOrder)
        <a href="{{ route('track.show', $workOrder->id) }}" class="block bg-white rounded-2xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        </svg>
                        <span class="font-medium text-gray-900 truncate">{{ $workOrder->location_full }}</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $workOrder->work_date->format('d M Y') }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $workOrder->start_time }} {{ $workOrder->end_time ? '- ' . $workOrder->end_time : '' }}
                        </span>
                    </div>
                </div>
                <span class="flex-shrink-0 px-3 py-1 text-xs font-semibold rounded-full {{ $workOrder->status === 'Final' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                    {{ $workOrder->status }}
                </span>
            </div>

            <!-- Photo Previews -->
            @if($workOrder->evidencePhotos->isNotEmpty())
                <div class="flex gap-1 mt-3">
                    @foreach($workOrder->evidencePhotos->take(4) as $photo)
                        <img src="{{ asset('storage/' . $photo->file_path) }}" class="w-12 h-12 object-cover rounded-lg">
                    @endforeach
                    @if($workOrder->evidencePhotos->count() > 4)
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-sm text-gray-500">
                            +{{ $workOrder->evidencePhotos->count() - 4 }}
                        </div>
                    @endif
                </div>
            @endif
        </a>
    @empty
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-1">Belum ada work order</h3>
            <p class="text-gray-500 mb-4">Mulai dengan membuat work order pertama Anda</p>
            <a href="{{ route('work-orders.create') }}" class="inline-block px-6 py-2 bg-primary hover:bg-primaryDark text-white rounded-xl transition font-medium">
                Buat Work Order
            </a>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($workOrders->hasPages())
    <div class="mt-6">
        {{ $workOrders->appends(request()->query())->links() }}
    </div>
@endif
@endsection
