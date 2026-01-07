<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FinalReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'end_time' => 'required|date_format:H:i',
            'bast_scan' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'end_time.required' => 'Jam selesai wajib diisi untuk Final Report.',
            'end_time.date_format' => 'Format jam harus HH:MM.',
            'bast_scan.required' => 'Scan BAST wajib diupload.',
            'bast_scan.mimes' => 'Format file harus PDF, JPG, JPEG, atau PNG.',
            'bast_scan.max' => 'Ukuran file maksimal 10MB.',
        ];
    }
}
