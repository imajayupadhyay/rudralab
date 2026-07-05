<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CertificateRequest;
use App\Models\Certificate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class CertificateController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim((string) $request->query('search', ''));

        $certificates = Certificate::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('certificate_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('origin', 'like', "%{$search}%")
                        ->orWhere('remarks', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString()
            ->through(fn (Certificate $certificate): array => [
                'id' => $certificate->id,
                'certificate_number' => $certificate->certificate_number,
                'customer_name' => $certificate->customer_name,
                'issued_at' => $certificate->issued_at?->format('d M Y'),
                'origin' => $certificate->origin,
                'is_active' => $certificate->is_active,
            ]);

        return Inertia::render('Admin/Certificates/Index', [
            'certificates' => $certificates,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Certificates/Create', [
            'certificate' => Certificate::emptyFormData(),
            'duplicateSource' => null,
        ]);
    }

    public function store(CertificateRequest $request): RedirectResponse
    {
        Certificate::create($this->validatedData($request));

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate created successfully.');
    }

    public function duplicate(Certificate $certificate): Response
    {
        return Inertia::render('Admin/Certificates/Create', [
            'certificate' => $certificate->duplicateFormData(),
            'duplicateSource' => [
                'id' => $certificate->id,
                'certificate_number' => $certificate->certificate_number,
            ],
        ]);
    }

    public function edit(Certificate $certificate): Response
    {
        return Inertia::render('Admin/Certificates/Edit', [
            'certificate' => $certificate->formData(),
        ]);
    }

    public function update(CertificateRequest $request, Certificate $certificate): RedirectResponse
    {
        $certificate->update($this->validatedData($request, $certificate));

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate updated successfully.');
    }

    public function destroy(Certificate $certificate): RedirectResponse
    {
        $certificate->delete();

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate deleted successfully.');
    }

    private function validatedData(CertificateRequest $request, ?Certificate $certificate = null): array
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        unset($data['image']);

        if ($request->hasFile('image')) {
            if ($certificate?->image_path && ! str_starts_with($certificate->image_path, '/')) {
                Storage::disk('public')->delete($certificate->image_path);
            }

            $data['image_path'] = $request->file('image')->store('certificates', 'public');
        }

        return $data;
    }
}
