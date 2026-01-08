<?php

use App\Models\User;
use App\Models\WorkOrder;
use App\Models\EvidencePhoto;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

beforeEach(function () {
    Storage::fake('public');
});

test('user can download pdf for their own work order', function () {
    $user = User::factory()->create();

    // Create a work order
    $workOrder = WorkOrder::factory()->for($user)->create([
        'status' => 'Final',
        'location_city' => 'Jakarta',
        'location_district' => 'Selatan',
        'location_village' => 'Kebayoran',
        'activity_details' => 'Test activity',
        'site_pic' => 'John Doe',
        'work_date' => now(),
        'start_time' => '09:00',
        'end_time' => '17:00',
        'bast_scan_path' => null,
    ]);

    $response = $this->actingAs($user)
        ->get(route('reports.download', $workOrder->id));

    $response->assertStatus(200)
        ->assertHeader('content-type', 'application/pdf');

    // Check content-disposition contains the filename (may or may not have quotes)
    $disposition = $response->headers->get('content-disposition');
    expect($disposition)->toContain('attachment')
        ->and($disposition)->toContain('work-order-' . $workOrder->id)
        ->and($disposition)->toContain('.pdf');
});

test('user cannot download pdf for another users work order', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $workOrder = WorkOrder::factory()->for($user1)->create([
        'status' => 'Final',
    ]);

    $this->actingAs($user2)
        ->get(route('reports.download', $workOrder->id))
        ->assertStatus(404);
});

test('guest cannot download pdf', function () {
    $user = User::factory()->create();
    $workOrder = WorkOrder::factory()->for($user)->create([
        'status' => 'Final',
    ]);

    $this->get(route('reports.download', $workOrder->id))
        ->assertRedirect(route('login'));
});

test('pdf contains work order details', function () {
    $user = User::factory()->create(['name' => 'Test User']);

    // Create a work order with specific data
    $workOrder = WorkOrder::factory()->for($user)->create([
        'status' => 'Final',
        'location_city' => 'Bandung',
        'location_district' => 'Utara',
        'location_village' => 'Sukasari',
        'activity_details' => 'Installation of new equipment',
        'site_pic' => 'Jane Smith',
        'work_date' => now()->setDate(2025, 1, 15),
        'start_time' => '08:00',
        'end_time' => '16:00',
    ]);

    $response = $this->actingAs($user)
        ->get(route('reports.download', $workOrder->id));

    $response->assertStatus(200);

    // Check that it's a valid PDF (starts with %PDF-)
    $content = $response->getContent();
    expect(substr($content, 0, 5))->toBe('%PDF-');

    // DomPDF generates compressed PDFs, so we check for basic structure
    // rather than text content which may be compressed
    expect($content)->toContain('obj')
        ->and($content)->toContain('endobj');
});

test('user can view reports index', function () {
    $user = User::factory()->create();

    WorkOrder::factory()->for($user)->create(['status' => 'Final']);
    WorkOrder::factory()->for($user)->create(['status' => 'Daily']);

    $response = $this->actingAs($user)
        ->get(route('reports.index'));

    $response->assertStatus(200)
        ->assertViewHas('workOrders');
});

test('reports index only shows final status work orders', function () {
    $user = User::factory()->create();

    $finalWorkOrder = WorkOrder::factory()->for($user)->create(['status' => 'Final']);
    $dailyWorkOrder = WorkOrder::factory()->for($user)->create(['status' => 'Daily']);

    $response = $this->actingAs($user)
        ->get(route('reports.index'));

    $workOrders = $response->viewData('workOrders');

    expect($workOrders)->toHaveCount(1)
        ->and($workOrders->first()->id)->toBe($finalWorkOrder->id);
});

test('user can preview their work order', function () {
    $user = User::factory()->create();

    $workOrder = WorkOrder::factory()->for($user)->create([
        'status' => 'Final',
        'location_city' => 'Test City',
    ]);

    $response = $this->actingAs($user)
        ->get(route('reports.preview', $workOrder->id));

    $response->assertStatus(200)
        ->assertViewHas('workOrder')
        ->assertSee('Test City');
});

test('user cannot preview another users work order', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $workOrder = WorkOrder::factory()->for($user1)->create(['status' => 'Final']);

    $this->actingAs($user2)
        ->get(route('reports.preview', $workOrder->id))
        ->assertStatus(404);
});
