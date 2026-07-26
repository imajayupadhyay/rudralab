<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Support\VerifyCertificatePageContent;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Inertia\Support\Header;
use Symfony\Component\HttpFoundation\Response;

class CertificateVerificationController extends Controller
{
    public function __invoke(Request $request): Response|InertiaResponse
    {
        // Verification URLs are public, shareable documents. Always make an
        // Inertia visit reload the full page so its JSON transport response can
        // never be restored later as the document by a browser or proxy cache.
        if ($request->header(Header::INERTIA)) {
            return Inertia::location($request->fullUrl());
        }

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
