<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\ContactSubmission;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Admin/Dashboard/Index', [
            'metrics' => [
                'certificates' => Certificate::count(),
                'activeCertificates' => Certificate::where('is_active', true)->count(),
                'inactiveCertificates' => Certificate::where('is_active', false)->count(),
                'contactSubmissions' => ContactSubmission::count(),
                'newContactSubmissions' => ContactSubmission::where('status', 'new')->count(),
                'admins' => User::where('is_admin', true)->count(),
            ],
            'recentCertificates' => Certificate::latest()
                ->take(5)
                ->get()
                ->map(fn (Certificate $certificate): array => [
                    'id' => $certificate->id,
                    'certificate_number' => $certificate->certificate_number,
                    'customer_name' => $certificate->customer_name,
                    'issued_at' => $certificate->issued_at?->format('d M Y'),
                    'is_active' => $certificate->is_active,
                ]),
        ]);
    }
}
