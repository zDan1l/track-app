<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkOrderRequest;
use App\Http\Requests\FinalReportRequest;
use App\Models\WorkOrder;
use App\Models\EvidencePhoto;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WorkOrderController extends Controller
{
    protected ImageUploadService $imageService;

    public function __construct(ImageUploadService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function create()
    {
        return view('work-orders.create');
    }

    public function store(WorkOrderRequest $request)
    {
        $workOrder = Auth::user()->workOrders()->create($request->validated());

        return redirect()->route('work-orders.show', $workOrder->id)
            ->with('success', 'Work Order berhasil dibuat.');
    }

    public function show($id)
    {
        $workOrder = WorkOrder::with(['user', 'evidencePhotos'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        // Group photos by category
        $photosByCategory = [
            'on_site' => $workOrder->evidencePhotos->where('category', 'on_site'),
            'work_area' => $workOrder->evidencePhotos->where('category', 'work_area'),
            'work_proof' => $workOrder->evidencePhotos->where('category', 'work_proof'),
            'other' => $workOrder->evidencePhotos->where('category', 'other'),
        ];

        return view('work-orders.show', compact('workOrder', 'photosByCategory'));
    }

    public function edit($id)
    {
        $workOrder = WorkOrder::where('user_id', Auth::id())
            ->where('status', 'Daily')
            ->findOrFail($id);

        return view('work-orders.edit', compact('workOrder'));
    }

    public function update(WorkOrderRequest $request, $id)
    {
        $workOrder = WorkOrder::where('user_id', Auth::id())
            ->where('status', 'Daily')
            ->findOrFail($id);

        $workOrder->update($request->validated());

        return redirect()->route('work-orders.show', $workOrder->id)
            ->with('success', 'Work Order berhasil diperbarui.');
    }

    /**
     * Upload evidence photo via AJAX
     */
    public function uploadEvidence(Request $request, $id): JsonResponse
    {
        $request->validate([
            'category' => 'required|in:on_site,work_area,work_proof,other',
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        $workOrder = WorkOrder::where('user_id', Auth::id())->findOrFail($id);

        if ($request->hasFile('photo')) {
            try {
                $result = $this->imageService->upload(
                    $request->file('photo'),
                    $workOrder->id,
                    $request->category
                );

                $photo = EvidencePhoto::create([
                    'work_order_id' => $workOrder->id,
                    'category' => $request->category,
                    'file_path' => $result['path'],
                    'original_name' => $result['filename'],
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Foto berhasil diupload.',
                    'photo' => [
                        'id' => $photo->id,
                        'url' => asset('storage/' . $result['path']),
                        'category' => $photo->category,
                        'size' => $result['size_human'],
                    ]
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengupload foto.',
        ], 422);
    }

    /**
     * Delete evidence photo via AJAX
     */
    public function deleteEvidence($id): JsonResponse
    {
        $photo = EvidencePhoto::findOrFail($id);
        $workOrder = $photo->workOrder;

        if ($workOrder->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
            ], 403);
        }

        if ($workOrder->status !== 'Daily') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya bisa menghapus foto dari Daily Report.',
            ], 422);
        }

        $this->imageService->delete($photo->file_path);
        $photo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Foto berhasil dihapus.',
        ]);
    }

    /**
     * Get photos for a work order (API)
     */
    public function getPhotos($id): JsonResponse
    {
        $workOrder = WorkOrder::where('user_id', Auth::id())->findOrFail($id);

        $photos = $workOrder->evidencePhotos->map(function ($photo) {
            return [
                'id' => $photo->id,
                'category' => $photo->category,
                'url' => asset('storage/' . $photo->file_path),
            ];
        });

        return response()->json([
            'success' => true,
            'photos' => $photos,
        ]);
    }

    public function submitDaily(Request $request, $id)
    {
        $workOrder = WorkOrder::where('user_id', Auth::id())->findOrFail($id);

        if (!$workOrder->hasRequiredEvidence()) {
            return back()->withErrors([
                'evidence' => 'Harap upload minimal 1 foto untuk setiap kategori (On Site, Area Pekerjaan, Bukti Pekerjaan).'
            ]);
        }

        $workOrder->update(['status' => 'Daily']);

        return redirect()->route('work-orders.show', $workOrder->id)
            ->with('success', 'Daily Report berhasil disubmit.');
    }

    public function submitFinal(FinalReportRequest $request, $id)
    {
        $workOrder = WorkOrder::where('user_id', Auth::id())->findOrFail($id);

        if (!$workOrder->hasRequiredEvidence()) {
            return back()->withErrors([
                'evidence' => 'Harap upload minimal 1 foto untuk setiap kategori (On Site, Area Pekerjaan, Bukti Pekerjaan).'
            ]);
        }

        $bastPath = $workOrder->bast_scan_path;

        if ($request->hasFile('bast_scan')) {
            $bastPath = $this->imageService->uploadBast(
                $request->file('bast_scan'),
                $workOrder->id
            );
        }

        $workOrder->update([
            'status' => 'Final',
            'end_time' => $request->end_time,
            'bast_scan_path' => $bastPath,
            'notes' => $request->notes,
        ]);

        return redirect()->route('work-orders.show', $workOrder->id)
            ->with('success', 'Final Report berhasil disubmit.');
    }

    public function finalForm($id)
    {
        $workOrder = WorkOrder::where('user_id', Auth::id())
            ->where('status', 'Daily')
            ->findOrFail($id);

        return view('work-orders.final', compact('workOrder'));
    }
}
