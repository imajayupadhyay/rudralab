<?php

namespace App\Http\Requests\Admin;

use App\Models\Certificate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $certificate = $this->route('certificate');
        $certificateId = $certificate instanceof Certificate ? $certificate->id : null;

        return [
            'certificate_number' => [
                'required',
                'string',
                'max:80',
                Rule::unique('certificates', 'certificate_number')->ignore($certificateId),
            ],
            'issued_at' => ['nullable', 'date'],
            'customer_name' => ['nullable', 'string', 'max:160'],
            'weight' => ['nullable', 'string', 'max:80'],
            'shape_cut' => ['nullable', 'string', 'max:120'],
            'dimension' => ['nullable', 'string', 'max:120'],
            'colour' => ['nullable', 'string', 'max:120'],
            'refractive_index' => ['nullable', 'string', 'max:120'],
            'specific_gravity' => ['nullable', 'string', 'max:120'],
            'origin' => ['nullable', 'string', 'max:160'],
            'remarks' => ['nullable', 'string', 'max:500'],
            'image_path' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:4096'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
