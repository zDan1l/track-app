# Implementation Plan
# TPAS Work Form - Field Work Order Management System

---

## Overview

This document outlines the step-by-step implementation plan for the TPAS Work Form application using Laravel 11, Tailwind CSS, and Alpine.js.

**Tech Stack:**
- Backend: Laravel 11 (PHP 8.2+)
- Frontend: Blade Templates, Tailwind CSS, Alpine.js
- Database: MySQL 8.0+
- PDF: laravel-dompdf
- Image Processing: intervention/image

---


## Colors

### Primary Colors
```css
/* Main Brand Colors */
--primary: #ff8563;        /* Soft Coral Orange - Primary CTA & Icons */
--primary-dark: #ff6b4a;   /* Darker Orange - Hover states */
--primary-light: #fff5f2;  /* Soft Peach - Backgrounds */

/* Secondary Accent */
--secondary: #ffb59a;      /* Light Coral - Secondary elements */
--accent: #ff9775;         /* Warm accent for highlights */
```

### Gradient Backgrounds
```css
/* Hero/Feature Gradients - Soft warm tones like in reference */
--gradient-warm: linear-gradient(135deg, #fff9f5 0%, #ffe8dc 30%, #ffd9c8 60%, #ffc9b5 100%);
--gradient-hero: linear-gradient(180deg, #fffbf8 0%, #fff3ed 50%, #ffe9e0 100%);
--gradient-card: linear-gradient(135deg, #ffffff 0%, #fffaf7 100%);

/* Accent Gradients */
--gradient-orange: linear-gradient(135deg, #ff8563 0%, #ff9775 100%);
--gradient-button: linear-gradient(135deg, #1f1f1f 0%, #2d2d2d 100%); /* Dark button gradient */
```

### Neutral Colors
```css
/* Text & UI - Berdasarkan referensi */
--gray-900: #1a1a1a;       /* Headings - lebih dark */
--gray-800: #2d2d2d;       /* Body text */
--gray-600: #5a5a5a;       /* Secondary text - lebih kontras */
--gray-400: #9a9a9a;       /* Disabled text */
--gray-200: #e5e5e5;       /* Borders */
--gray-100: #f8f8f8;       /* Light backgrounds */
--gray-50: #fafafa;        /* Subtle backgrounds */
--white: #ffffff;
--off-white: #fefefe;      /* Slight warm tint */
```

### Semantic Colors
```css
/* Status Colors */
--success: #10b981;        /* Approved/Success */
--success-light: #d1fae5;  /* Success background */

--warning: #f59e0b;        /* Pending/Warning */
--warning-light: #fef3c7;  /* Warning background */

--error: #ef4444;          /* Rejected/Error */
--error-light: #fee2e2;    /* Error background */

--info: #3b82f6;           /* Information */
--info-light: #dbeafe;     /* Info background */
```

---

## Typography

### Font Family
```css
--font-sans: "Plus Jakarta Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
--font-display: "Plus Jakarta Sans", sans-serif; /* For headings */
--font-mono: "JetBrains Mono", "Fira Code", monospace;
```

**Font Import:**
```html
<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
```


## Phase 1: Project Setup & Foundation

### 1.1 Initial Setup
- [ ] Install Laravel 11 via Composer
- [ ] Configure `.env` file (database, app name, url)
- [ ] Set up MySQL database schema
- [ ] Configure timezone to Asia/Jakarta

### 1.2 Dependencies Installation
```bash
composer require barryvdh/laravel-dompdf
composer require intervention/image
npm install tailwindcss alpinejs
```

### 1.3 Frontend Setup
- [ ] Configure Tailwind CSS (mobile-first approach)
- [ ] Set up Alpine.js
- [ ] Create base layout template
- [ ] Configure Vite for asset compilation

### 1.4 Directory Structure
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── WorkOrderController.php
│   │   └── ReportController.php
│   ├── Middleware/
│   │   └── CheckRole.php
│   └── Requests/
│       ├── WorkOrderRequest.php
│       └── FinalReportRequest.php
├── Models/
│   ├── User.php
│   ├── WorkOrder.php
│   └── EvidencePhoto.php
resources/
├── views/
│   ├── layouts/
│   │   └── app.blade.php
│   ├── auth/
│   ├── dashboard/
│   ├── work-orders/
│   └── reports/
routes/
├── web.php
└── api.php (optional)
```

---

## Phase 2: Authentication System

### 2.1 Database Migration
```php
// Laravel default users table + additional fields
- name (string)
- email (string, unique)
- password (string)
- role (enum: 'technician', 'admin')
```

### 2.2 Authentication Features
| Feature | Implementation |
|---------|----------------|
| Login | Laravel Breeze or custom auth |
| Logout | POST route with CSRF |
| Session Management | Laravel default session |
| Remember Me | Laravel built-in feature |

### 2.3 Routes
```php
// Public routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    // ... other routes
});
```

### 2.4 UI Components
- Login form with email/password
- Remember me checkbox
- Error/success notifications
- Mobile-responsive layout

---

## Phase 3: Dashboard & Navigation

### 3.1 Dashboard Features
| Component | Description |
|-----------|-------------|
| Header | Hamburger menu + User profile |
| Quick Access Cards | 3 large cards: Input, Track, Download |
| Status Summary | Count of Daily/Final work orders |
| Recent Activity | Last 5 work orders |

### 3.2 Navigation Structure
```
┌──────────────────────────────────────┐
│  ☰  TPAS Work Form           [User]  │  <-- Header
├──────────────────────────────────────┤
│  ┌────────────┐  ┌────────────┐      │  <-- Quick Access Cards
│  │   INPUT    │  │   TRACK    │      │
│  │ Work Order │  │ Work Order │      │
│  └────────────┘  └────────────┘      │
│                                      │
│  ┌────────────────────────────┐      │
│  │       DOWNLOAD             │      │
│  │       Report               │      │
│  └────────────────────────────┘      │
└──────────────────────────────────────┘
```

### 3.3 Sidebar/Hamburger Menu (Mobile)
```
┌─────────────────┐
│  TPAS Work Form │
├─────────────────┤
│  Dashboard      │
│  Input Work     │
│  Track Work     │
│  Download Report│
│  Logout         │
└─────────────────┘
```

### 3.4 Alpine.js Components
```javascript
// Mobile menu toggle
Alpine.data('mobileMenu', () => ({
    open: false,
    toggle() { this.open = !this.open }
}))
```

---

## Phase 4: Work Order Module (Input)

### 4.1 Database Migration
```php
Schema::create('work_orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('location_city');
    $table->string('location_district');
    $table->string('location_village');
    $table->date('work_date');
    $table->time('start_time');
    $table->time('end_time')->nullable();
    $table->text('activity_details');
    $table->string('site_pic');
    $table->enum('status', ['Daily', 'Final'])->default('Daily');
    $table->string('bast_scan_path')->nullable();
    $table->text('notes')->nullable();
    $table->timestamps();
});
```

### 4.2 Form Fields
| Field | Type | Validation | Required |
|-------|------|------------|----------|
| Kota | text/select | required | Yes |
| Kecamatan | text/select | required | Yes |
| Desa/Kelurahan | text/select | required | Yes |
| Tanggal Kerja | date | required|date_format:Y-m-d | Yes |
| Jam Mulai | time | required|date_format:H:i | Yes |
| Jam Selesai | time | nullable|date_format:H:i | No* |
| Jenis Aktivitas | textarea | required|min:10 | Yes |
| Site PIC | text | required|max:100 | Yes |
| Catatan | textarea | nullable | No |

* Required for Final Report only

### 4.3 Controller Methods
```php
class WorkOrderController extends Controller
{
    public function create() // Show input form
    public function store(WorkOrderRequest $request) // Save draft
    public function show($id) // Show detail
    public function edit($id) // Edit draft only
    public function update($request, $id) // Update draft
}
```

### 4.4 Form Validation
```php
// WorkOrderRequest
public function rules(): array
{
    return [
        'location_city' => 'required|string|max:100',
        'location_district' => 'required|string|max:100',
        'location_village' => 'required|string|max:100',
        'work_date' => 'required|date_format:Y-m-d',
        'start_time' => 'required|date_format:H:i',
        'activity_details' => 'required|string|min:10',
        'site_pic' => 'required|string|max:100',
        'notes' => 'nullable|string',
    ];
}
```

---

## Phase 5: Evidence Photo Upload

### 5.1 Database Migration
```php
Schema::create('evidence_photos', function (Blueprint $table) {
    $table->id();
    $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
    $table->string('category'); // 'on_site', 'work_area', 'work_proof', 'other'
    $table->string('file_path');
    $table->string('original_name');
    $table->timestamps();
});
```

### 5.2 Photo Categories
| Category | Required | Description |
|----------|----------|-------------|
| on_site | Yes | Foto lokasi/site |
| work_area | Yes | Foto area kerja |
| work_proof | Yes | Foto hasil pekerjaan |
| other | No | Dokumentasi tambahan |

### 5.3 Image Processing
```php
// Service: ImageUploadService
- Compress image to max 1MB
- Resize to max 1920px width
- Convert to JPG if needed
- Generate unique filename
- Store in storage/app/public/evidence/{work_order_id}/
```

### 5.4 Upload Handler
```php
public function uploadEvidence(Request $request, $workOrderId)
{
    $request->validate([
        'category' => 'required|in:on_site,work_area,work_proof,other',
        'photo' => 'required|image|mimes:jpg,jpeg,png|max:5120', // 5MB max before compression
    ]);

    // Compress & save
    $path = ImageService::uploadAndCompress($request->file('photo'), $workOrderId);

    EvidencePhoto::create([
        'work_order_id' => $workOrderId,
        'category' => $request->category,
        'file_path' => $path,
        'original_name' => $request->file('photo')->getClientOriginalName(),
    ]);
}
```

### 5.5 Frontend Features
- Direct camera access on mobile (`capture="environment"`)
- Image preview before upload
- Progress indicator
- Grid layout for multiple photos
- Delete photo functionality (before submission)

---

## Phase 6: Daily Report Feature

### 6.1 Requirements
| Field | Requirement |
|-------|-------------|
| Status | Auto-set to "Daily" |
| Jam Selesai | NOT required |
| Evidence | Min 1 photo per required category |
| Submit Button | With confirmation modal |

### 6.2 Controller Method
```php
public function submitDaily(Request $request, $id)
{
    $workOrder = WorkOrder::findOrFail($id);

    // Validate evidence photos
    $requiredCategories = ['on_site', 'work_area', 'work_proof'];
    foreach ($requiredCategories as $category) {
        if (!$workOrder->evidencePhotos()->where('category', $category)->exists()) {
            return back()->withErrors(["{$category}_photo" => "Photo {$category} wajib diisi"]);
        }
    }

    $workOrder->update(['status' => 'Daily']);

    return redirect()->route('work-orders.show', $id)
        ->with('success', 'Daily Report berhasil disubmit');
}
```

### 6.3 UI Components
- Submit button (blue primary)
- Confirmation modal
- Status badge (yellow)
- Success toast notification

---

## Phase 7: Final Report Feature

### 7.1 Requirements
| Field | Requirement |
|-------|-------------|
| Status | Auto-set to "Final" |
| Jam Selesai | REQUIRED |
| Scan BAST | REQUIRED (PDF/IMAGE) |
| Evidence | Same as Daily Report |

### 7.2 Database Update
The `work_orders` table already has:
- `end_time` (TIME, NULLABLE) → Required for Final
- `bast_scan_path` (VARCHAR, NULLABLE) → Required for Final

### 7.3 Controller Method
```php
public function submitFinal(FinalReportRequest $request, $id)
{
    $workOrder = WorkOrder::findOrFail($id);

    // Validate end_time
    if (empty($request->end_time)) {
        return back()->withErrors(['end_time' => 'Jam Selesai wajib diisi untuk Final Report']);
    }

    // Validate BAST upload
    if (!$request->hasFile('bast_scan')) {
        return back()->withErrors(['bast_scan' => 'Scan BAST wajib diupload']);
    }

    // Validate evidence
    $requiredCategories = ['on_site', 'work_area', 'work_proof'];
    foreach ($requiredCategories as $category) {
        if (!$workOrder->evidencePhotos()->where('category', $category)->exists()) {
            return back()->withErrors(["{$category}_photo" => "Photo {$category} wajib diisi"]);
        }
    }

    // Upload BAST
    $bastPath = $request->file('bast_scan')->store('bast', 'public');

    $workOrder->update([
        'status' => 'Final',
        'end_time' => $request->end_time,
        'bast_scan_path' => $bastPath,
        'notes' => $request->notes,
    ]);

    return redirect()->route('work-orders.show', $id)
        ->with('success', 'Final Report berhasil disubmit');
}
```

### 7.4 Validation Rules
```php
// FinalReportRequest
public function rules(): array
{
    return [
        'end_time' => 'required|date_format:H:i',
        'bast_scan' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB
        'notes' => 'nullable|string',
    ];
}
```

---

## Phase 8: Track Work Order Module

### 8.1 Features
| Feature | Description |
|---------|-------------|
| List View | Cards showing all work orders |
| Status Badge | Yellow (Daily) / Green (Final) |
| Search | By location (city, district, village) |
| Filter | By status, date range |
| Pagination | 20 items per page |
| Detail View | Click card to see full detail |

### 8.2 Card Layout
```
┌─────────────────────────────────────┐
│ 📍 Jakarta, Selatan, Kebagusan      │
│ 📅 12 Jan 2026    ⏰ 08:00 - --:--  │
│ 👤 John Doe                          │
│ ┌──────────┐  ┌──────────────────┐  │
│ │  DAILY   │  │  View Detail →   │  │
│ └──────────┘  └──────────────────┘  │
└─────────────────────────────────────┘
```

### 8.3 Controller Method
```php
class TrackController extends Controller
{
    public function index(Request $request)
    {
        $query = WorkOrder::with(['user', 'evidencePhotos'])
            ->where('user_id', auth()->id());

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by location
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('location_city', 'like', "%{$search}%")
                  ->orWhere('location_district', 'like', "%{$search}%")
                  ->orWhere('location_village', 'like', "%{$search}%");
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('work_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('work_date', '<=', $request->date_to);
        }

        $workOrders = $query->latest()->paginate(20);

        return view('track.index', compact('workOrders'));
    }

    public function show($id)
    {
        $workOrder = WorkOrder::with(['user', 'evidencePhotos'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('track.show', compact('workOrder'));
    }
}
```

### 8.4 Routes
```php
Route::prefix('track')->name('track.')->group(function () {
    Route::get('/', [TrackController::class, 'index'])->name('index');
    Route::get('/{id}', [TrackController::class, 'show'])->name('show');
});
```

### 8.5 Alpine.js for Filters
```javascript
Alpine.data('trackFilters', () => ({
    status: '',
    search: '',
    dateFrom: '',
    dateTo: '',
    applyFilters() {
        // Submit form or update URL params
    }
}))
```

---

## Phase 9: PDF Generation & Download

### 9.1 Dependencies
```bash
composer require barryvdh/laravel-dompdf
```

### 9.2 PDF Template Structure
```blade
<!-- resources/views/reports/pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <style>
        /* PDF-specific styles */
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .section { margin-bottom: 15px; }
        .photos { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
        .photo { width: 100%; height: 150px; object-fit: cover; }
    </style>
</head>
<body>
    <!-- 1. Header -->
    <div class="header">
        <h1>TPAS Work Form Report</h1>
        <p>Generated: {{ now()->format('d M Y H:i') }}</p>
    </div>

    <!-- 2. Location Info -->
    <div class="section">
        <h3>Lokasi Pekerjaan</h3>
        <p>{{ $workOrder->location_city }}, {{ $workOrder->location_district }},
           {{ $workOrder->location_village }}</p>
    </div>

    <!-- 3. Activity Details -->
    <div class="section">
        <h3>Detail Aktivitas</h3>
        <p>{{ $workOrder->activity_details }}</p>
        <p><strong>PIC:</strong> {{ $workOrder->site_pic }}</p>
    </div>

    <!-- 4. Timeline -->
    <div class="section">
        <h3>Waktu Pelaksanaan</h3>
        <p>{{ $workOrder->work_date->format('d M Y') }}</p>
        <p>{{ $workOrder->start_time }} - {{ $workOrder->end_time ?? '--:--' }}</p>
    </div>

    <!-- 5. Evidence Photos -->
    <div class="section">
        <h3>Dokumentasi</h3>
        <div class="photos">
            @foreach($workOrder->evidencePhotos as $photo)
                <img src="{{ asset('storage/' . $photo->file_path) }}" class="photo">
            @endforeach
        </div>
    </div>

    <!-- 6. BAST Scan (if Final) -->
    @if($workOrder->status === 'Final' && $workOrder->bast_scan_path)
    <div class="section">
        <h3>Berita Acara Serah Terima</h3>
        <img src="{{ asset('storage/' . $workOrder->bast_scan_path) }}" style="max-width: 100%;">
    </div>
    @endif

    <!-- 7. Footer/Signature -->
    <div class="section" style="margin-top: 50px;">
        <p>Dibuat oleh: {{ $workOrder->user->name }}</p>
        <p>Status: {{ $workOrder->status }}</p>
    </div>
</body>
</html>
```

### 9.3 Controller Methods
```php
class ReportController extends Controller
{
    public function index()
    {
        $workOrders = WorkOrder::where('user_id', auth()->id())
            ->where('status', 'Final')
            ->latest()
            ->get();

        return view('reports.index', compact('workOrders'));
    }

    public function preview($id)
    {
        $workOrder = WorkOrder::with(['user', 'evidencePhotos'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('reports.preview', compact('workOrder'));
    }

    public function download($id)
    {
        $workOrder = WorkOrder::with(['user', 'evidencePhotos'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $pdf = PDF::loadView('reports.pdf', compact('workOrder'));
        $filename = "work-order-{$id}-" . now()->format('YmdHis') . '.pdf';

        return $pdf->download($filename);
    }
}
```

### 9.4 Routes
```php
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/{id}/preview', [ReportController::class, 'preview'])->name('preview');
    Route::get('/{id}/download', [ReportController::class, 'download'])->name('download');
});
```

---

## Phase 10: Testing & Optimization

### 10.1 Testing Checklist
| Component | Test Cases |
|-----------|------------|
| Auth | Login valid, Login invalid, Logout, Session expire |
| Input Form | All fields validation, Save draft, Edit draft |
| Photo Upload | Valid formats, File size, Compression, Multiple upload |
| Daily Report | Submit without end_time, Evidence validation |
| Final Report | Submit with all required, Missing BAST error |
| Track | Search, Filter, Pagination, Detail view |
| PDF | Generate, Download, Format check |

### 10.2 Performance Optimization
- Lazy load images in track list
- Implement pagination for work order list
- Optimize image compression settings
- Add database indexes on frequently queried columns
- Cache static data (location dropdowns)

### 10.3 Security Checklist
- [ ] CSRF protection on all forms
- [ ] XSS sanitization on all inputs
- [ ] SQL injection prevention (use Eloquent)
- [ ] File upload validation (type, size)
- [ ] Route protection with middleware
- [ ] Rate limiting on login attempts

### 10.4 Mobile Testing
- [ ] Test on Chrome Mobile
- [ ] Test on Safari iOS
- [ ] Camera access functionality
- [ ] Touch target sizes (min 44x44px)
- [ ] Offline behavior considerations

---

## Summary of Routes

| Method | Route | Controller | Description |
|--------|-------|------------|-------------|
| GET | `/login` | AuthController@showLogin | Login page |
| POST | `/login` | AuthController@login | Authenticate |
| POST | `/logout` | AuthController@logout | Logout |
| GET | `/` | DashboardController@index | Dashboard |
| GET | `/work-orders/create` | WorkOrderController@create | Input form |
| POST | `/work-orders` | WorkOrderController@store | Save draft |
| GET | `/work-orders/{id}` | WorkOrderController@show | Detail view |
| POST | `/work-orders/{id}/daily` | WorkOrderController@submitDaily | Submit daily |
| POST | `/work-orders/{id}/final` | WorkOrderController@submitFinal | Submit final |
| POST | `/work-orders/{id}/upload` | WorkOrderController@uploadEvidence | Upload photo |
| GET | `/track` | TrackController@index | List all |
| GET | `/track/{id}` | TrackController@show | Detail |
| GET | `/reports` | ReportController@index | List reports |
| GET | `/reports/{id}/preview` | ReportController@preview | Preview PDF |
| GET | `/reports/{id}/download` | ReportController@download | Download PDF |

---

## Environment Configuration

### `.env` Template
```env
APP_NAME="TPAS Work Form"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

APP_TIMEZONE=Asia/Jakarta
APP_LOCALE=id

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tpas_work_form
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## Migration Execution Order

```bash
# Run in order
php artisan migrate
```

1. `users` (Laravel default)
2. `work_orders` (custom)
3. `evidence_photos` (custom)
4. `password_reset_tokens` (Laravel default)
5. `failed_jobs` (Laravel default)
6. `jobs` (Laravel default - if using queues)

---

## Commands Quick Reference

```bash
# Installation
composer create-project laravel/laravel tpas-work-form
cd tpas-work-form

# Dependencies
composer require barryvdh/laravel-dompdf intervention/image
npm install

# Setup
cp .env.example .env
php artisan key:generate
php artisan migrate

# Assets
npm run build

# Development
php artisan serve
npm run dev

# Production
npm run build
php artisan optimize
php artisan config:cache
php artisan route:cache
```

---

## Notes & Considerations

1. **Mobile Optimization**: All forms should be touch-friendly with large input fields and buttons
2. **Offline Consideration**: Consider using localStorage for form data backup
3. **Image Compression**: Balance between quality and file size for PDF generation
4. **PDF Performance**: Consider queue generation for large reports
5. **Database Indexing**: Add indexes on `user_id`, `status`, `work_date` for query optimization

---

## Next Steps

Once this plan is approved, implementation can proceed phase by phase. Each phase should be completed and tested before moving to the next phase.

**Suggested workflow:**
1. Complete Phase 1-3 (Foundation)
2. Complete Phase 4-5 (Input & Upload)
3. Complete Phase 6-7 (Reports)
4. Complete Phase 8-9 (Track & PDF)
5. Complete Phase 10 (Testing)

---

*Document Version: 1.0*
*Last Updated: 7 January 2026*
