<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Support\VerifyCertificatePageContent;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CertificateVerificationController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $searched = Certificate::normalizeNumber((string) $request->query('certificate', ''));
        $certificate = null;

        if ($searched !== '') {
            $certificate = Certificate::query()
                ->where('normalized_certificate_number', $searched)
                ->where('is_active', true)
                ->first();
        }

        return Inertia::render('VerifyCertificate/index', [
            'content' => VerifyCertificatePageContent::get(),
            'initialSearch' => $searched,
            'certificate' => $certificate?->verificationPayload(),
            'certificateSearched' => $searched,
        ]);
    }
}
