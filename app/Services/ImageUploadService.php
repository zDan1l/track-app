<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageUploadService
{
    protected ImageManager $image;
    protected int $maxWidth = 1920;
    protected int $maxHeight = 1080;
    protected int $quality = 85;
    protected int $maxFileSize = 1048576; // 1MB in bytes

    public function __construct()
    {
        $this->image = new ImageManager(new Driver());
    }

    /**
     * Upload and compress image
     */
    public function upload($file, int $workOrderId, string $category): array
    {
        // Validate file
        $this->validateFile($file);

        // Load and process image
        $image = $this->image->read($file);

        // Resize if needed (maintain aspect ratio)
        $image = $this->resizeIfNeeded($image);

        // Generate unique filename
        $filename = $this->generateFilename($file);

        // Create directory path
        $relativePath = "evidence/{$workOrderId}/{$category}";
        $fullPath = $relativePath . '/' . $filename;

        // Store with compression
        $this->storeImage($image, $fullPath);

        // Get file size
        $fileSize = $this->getFileSize($fullPath);

        return [
            'path' => $fullPath,
            'filename' => $filename,
            'size' => $fileSize,
            'size_human' => $this->formatFileSize($fileSize),
            'category' => $category,
        ];
    }

    /**
     * Upload BAST document
     */
    public function uploadBast($file, int $workOrderId): string
    {
        $filename = 'bast_' . $workOrderId . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('bast', $filename, 'public');

        return $path;
    }

    /**
     * Delete image
     */
    public function delete(string $path): bool
    {
        return Storage::disk('public')->delete($path);
    }

    /**
     * Validate uploaded file
     */
    protected function validateFile($file): void
    {
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \InvalidArgumentException('Format file harus JPG, PNG, atau WebP.');
        }

        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $allowedExtensions)) {
            throw new \InvalidArgumentException('Ekstensi file tidak diizinkan.');
        }

        if ($file->getSize() > 10485760) { // 10MB max before compression
            throw new \InvalidArgumentException('Ukuran file maksimal 10MB.');
        }
    }

    /**
     * Resize image if needed while maintaining aspect ratio
     */
    protected function resizeIfNeeded($image)
    {
        $width = $image->width();
        $height = $image->height();

        if ($width > $this->maxWidth || $height > $this->maxHeight) {
            $image->scaleDown($this->maxWidth, $this->maxHeight);
        }

        return $image;
    }

    /**
     * Generate unique filename
     */
    protected function generateFilename($file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $basename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $timestamp = now()->format('YmdHis');
        $random = Str::random(4);

        return "{$timestamp}_{$basename}_{$random}.{$extension}";
    }

    /**
     * Store image with compression
     */
    protected function storeImage($image, string $path): void
    {
        $encoded = $image->toJpeg(quality: $this->quality);

        // Keep compressing if file is still too large
        $quality = $this->quality;
        while (strlen($encoded) > $this->maxFileSize && $quality > 50) {
            $quality -= 10;
            $encoded = $image->toJpeg(quality: $quality);
        }

        Storage::disk('public')->put($path, $encoded);
    }

    /**
     * Get file size
     */
    protected function getFileSize(string $path): int
    {
        return Storage::disk('public')->size($path);
    }

    /**
     * Format file size for display
     */
    protected function formatFileSize(int $bytes): string
    {
        if ($bytes < 1048576) {
            return round($bytes / 1024, 1) . ' KB';
        }
        return round($bytes / 1048576, 2) . ' MB';
    }
}
