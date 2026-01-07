<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 20mm; }
        body {
            font-family: sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #1a1a1a;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #ff8563;
        }
        .header h1 {
            font-size: 20px;
            margin: 0 0 5px 0;
            color: #1a1a1a;
        }
        .header p {
            font-size: 10px;
            color: #666;
            margin: 0;
        }
        .section {
            margin-bottom: 18px;
        }
        .section-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 6px;
        }
        .section-content {
            font-size: 12px;
        }
        .location {
            font-size: 14px;
            font-weight: bold;
        }
        .photos {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-top: 8px;
        }
        .photo {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 4px;
        }
        .photo-label {
            font-size: 8px;
            color: #666;
            margin-top: 2px;
        }
        .bast-image {
            max-width: 100%;
            height: auto;
            margin-top: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }
        .footer-row {
            display: flex;
            justify-content: space-between;
        }
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-daily { background: #fef3c7; color: #92400e; }
        .badge-final { background: #d1fae5; color: #065f46; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        td {
            padding: 4px 0;
            vertical-align: top;
        }
        .label { color: #666; font-size: 10px; }
        .value { font-weight: 500; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>TPAS Work Form Report</h1>
        <p>Generated: {{ now()->format('d M Y H:i') }}</p>
    </div>

    <!-- Location Info -->
    <div class="section">
        <div class="section-title">Lokasi Pekerjaan</div>
        <div class="location">{{ $workOrder->location_city }}, {{ $workOrder->location_district }}, {{ $workOrder->location_village }}</div>
    </div>

    <!-- Activity Details -->
    <div class="section">
        <div class="section-title">Detail Aktivitas</div>
        <div class="section-content">
            <p>{{ $workOrder->activity_details }}</p>
            <table>
                <tr>
                    <td width="100" class="label">Site PIC:</td>
                    <td class="value">{{ $workOrder->site_pic }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Timeline -->
    <div class="section">
        <div class="section-title">Waktu Pelaksanaan</div>
        <div class="section-content">
            <p>{{ $workOrder->work_date->format('d M Y') }}</p>
            <p>{{ $workOrder->start_time }} - {{ $workOrder->end_time ?? '--:--' }}</p>
        </div>
    </div>

    <!-- Evidence Photos -->
    @if($workOrder->evidencePhotos->isNotEmpty())
    <div class="section">
        <div class="section-title">Dokumentasi</div>
        @foreach(['on_site' => 'On Site', 'work_area' => 'Area Pekerjaan', 'work_proof' => 'Bukti Pekerjaan', 'other' => 'Dokumentasi Lain'] as $key => $label)
            @php $photos = $workOrder->evidencePhotos->where('category', $key); @endphp
            @if($photos->isNotEmpty())
                <div style="margin-bottom: 10px;">
                    <p class="section-title">{{ $label }}</p>
                    <div class="photos">
                        @foreach($photos as $photo)
                            <div>
                                <img src="{{ asset('storage/' . $photo->file_path) }}" class="photo">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    @endif

    <!-- BAST Scan -->
    @if($workOrder->status === 'Final' && $workOrder->bast_scan_path)
    <div class="section">
        <div class="section-title">Berita Acara Serah Terima</div>
        <img src="{{ asset('storage/' . $workOrder->bast_scan_path) }}" class="bast-image">
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div class="footer-row">
            <div>
                <p class="label">Dibuat oleh:</p>
                <p class="value">{{ $workOrder->user->name }}</p>
            </div>
            <div style="text-align: right;">
                <p class="label">Status:</p>
                <span class="badge {{ $workOrder->status === 'Final' ? 'badge-final' : 'badge-daily' }}">
                    {{ $workOrder->status }}
                </span>
            </div>
        </div>
    </div>
</body>
</html>
