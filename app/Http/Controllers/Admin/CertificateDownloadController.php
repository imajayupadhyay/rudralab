<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Support\CertificatePdfRenderer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class CertificateDownloadController extends Controller
{
    public function show(Certificate $certificate, string $cardType, CertificatePdfRenderer $renderer): Response
    {
        abort_unless($certificate->supportsCardType($cardType), 404);

        $pdf = $renderer->render($certificate, $cardType);
        $filename = $renderer->filename($certificate, $cardType);

        return response($pdf, 200, [
            'Cache-Control' => 'private, no-store, max-age=0',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Content-Length' => (string) strlen($pdf),
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function bulk(Request $request, CertificatePdfRenderer $renderer): RedirectResponse|BinaryFileResponse
    {
        $validated = $request->validate([
            'card_type' => ['required', Rule::in(Certificate::downloadableCardTypes())],
            'certificate_ids' => ['required', 'array', 'min:1'],
            'certificate_ids.*' => ['integer', 'distinct', 'exists:certificates,id'],
        ]);

        /** @var list<int> $ids */
        $ids = array_map('intval', $validated['certificate_ids']);
        $cardType = (string) $validated['card_type'];
        $certificates = $this->certificatesInRequestOrder($ids)
            ->filter(fn (Certificate $certificate): bool => $certificate->supportsCardType($cardType))
            ->values();

        if ($certificates->isEmpty()) {
            return back()->with('error', 'None of the selected certificates has the requested certificate type.');
        }

        if (! class_exists(ZipArchive::class)) {
            return back()->with('error', 'Bulk PDF download requires the PHP zip extension.');
        }

        $zipDirectory = storage_path('app/certificate-downloads');
        File::ensureDirectoryExists($zipDirectory);

        $zipPath = $zipDirectory.'/rbtl-certificates-'.uniqid('', true).'.zip';
        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'The certificate archive could not be prepared.');
        }

        $usedNames = [];

        foreach ($certificates as $certificate) {
            $entryName = $renderer->filename($certificate, $cardType);

            if (isset($usedNames[$entryName])) {
                $entryName = $certificate->id.'-'.$entryName;
            }

            $usedNames[$entryName] = true;
            $zip->addFromString($entryName, $renderer->render($certificate, $cardType));
        }

        $zip->close();

        $downloadName = sprintf(
            'rbtl-%s-%s.zip',
            $renderer->typeSlug($cardType),
            now()->format('Ymd-His'),
        );

        return response()->download($zipPath, $downloadName, [
            'Cache-Control' => 'private, no-store, max-age=0',
            'Content-Type' => 'application/zip',
        ])->deleteFileAfterSend(true);
    }

    /**
     * @param  list<int>  $ids
     * @return Collection<int, Certificate>
     */
    private function certificatesInRequestOrder(array $ids): Collection
    {
        $positions = array_flip($ids);

        return Certificate::query()
            ->whereIn('id', $ids)
            ->get()
            ->sortBy(fn (Certificate $certificate): int => $positions[$certificate->id] ?? PHP_INT_MAX)
            ->values();
    }
}
