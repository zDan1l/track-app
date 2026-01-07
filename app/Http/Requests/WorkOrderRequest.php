<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

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

    public function messages(): array
    {
        return [
            'location_city.required' => 'Kota wajib diisi.',
            'location_district.required' => 'Kecamatan wajib diisi.',
            'location_village.required' => 'Desa/Kelurahan wajib diisi.',
            'work_date.required' => 'Tanggal kerja wajib diisi.',
            'work_date.date_format' => 'Format tanggal harus YYYY-MM-DD.',
            'start_time.required' => 'Jam mulai wajib diisi.',
            'start_time.date_format' => 'Format jam harus HH:MM.',
            'activity_details.required' => 'Detail aktivitas wajib diisi.',
            'activity_details.min' => 'Detail aktivitas minimal 10 karakter.',
            'site_pic.required' => 'Nama Site PIC wajib diisi.',
        ];
    }
}
