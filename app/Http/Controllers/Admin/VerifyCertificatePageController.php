<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\VerifyCertificatePageContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class VerifyCertificatePageController extends Controller
{
    public function edit(): Response
    {
        return Inertia::render('Admin/VerifyCertificatePage/Edit', [
            'content' => VerifyCertificatePageContent::get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $content = $request->validate([
            'content' => ['required', 'array'],
            'content.hero' => ['required', 'array'],
            'content.hero.badge' => ['nullable', 'string', 'max:160'],
            'content.hero.title' => ['nullable', 'string', 'max:220'],
            'content.hero.highlight' => ['nullable', 'string', 'max:220'],
            'content.hero.description' => ['nullable', 'string', 'max:1200'],
            'content.form' => ['required', 'array'],
            'content.form.label' => ['nullable', 'string', 'max:120'],
            'content.form.placeholder' => ['nullable', 'string', 'max:160'],
            'content.form.button_text' => ['nullable', 'string', 'max:80'],
            'content.form.empty_message' => ['nullable', 'string', 'max:220'],
            'content.form.demo_prefix' => ['nullable', 'string', 'max:160'],
            'content.form.demo_certificate' => ['nullable', 'string', 'max:80'],
            'content.result' => ['required', 'array'],
            'content.result.verified_label' => ['nullable', 'string', 'max:120'],
            'content.result.issued_prefix' => ['nullable', 'string', 'max:80'],
            'content.result.image_alt' => ['nullable', 'string', 'max:160'],
            'content.result.registry_note' => ['nullable', 'string', 'max:1000'],
            'content.result.not_found_title' => ['nullable', 'string', 'max:160'],
            'content.result.not_found_prefix' => ['nullable', 'string', 'max:240'],
            'content.result.not_found_suffix' => ['nullable', 'string', 'max:300'],
            'content.result.field_labels' => ['required', 'array'],
            'content.result.field_labels.certificate' => ['nullable', 'string', 'max:80'],
            'content.result.field_labels.weight' => ['nullable', 'string', 'max:80'],
            'content.result.field_labels.shape_cut' => ['nullable', 'string', 'max:80'],
            'content.result.field_labels.dimension' => ['nullable', 'string', 'max:80'],
            'content.result.field_labels.colour' => ['nullable', 'string', 'max:80'],
            'content.result.field_labels.refractive_index' => ['nullable', 'string', 'max:80'],
            'content.result.field_labels.specific_gravity' => ['nullable', 'string', 'max:80'],
            'content.result.field_labels.origin' => ['nullable', 'string', 'max:80'],
            'content.result.field_labels.issued_to' => ['nullable', 'string', 'max:80'],
            'content.result.field_labels.remarks' => ['nullable', 'string', 'max:80'],
            'content.certificate_preview' => ['required', 'array'],
            'content.certificate_preview.logo_text' => ['nullable', 'string', 'max:10'],
            'content.certificate_preview.brand_title' => ['nullable', 'string', 'max:160'],
            'content.certificate_preview.brand_tagline' => ['nullable', 'string', 'max:180'],
            'content.certificate_preview.report_title' => ['nullable', 'string', 'max:120'],
            'content.certificate_preview.qr_alt' => ['nullable', 'string', 'max:160'],
            'content.certificate_preview.item_alt' => ['nullable', 'string', 'max:160'],
            'content.certificate_preview.signature_name' => ['nullable', 'string', 'max:80'],
            'content.certificate_preview.signature_label' => ['nullable', 'string', 'max:120'],
            'content.help' => ['required', 'array', 'max:6'],
            'content.help.*.n' => ['nullable', 'string', 'max:12'],
            'content.help.*.t' => ['nullable', 'string', 'max:240'],
        ])['content'];

        VerifyCertificatePageContent::save($content);

        return redirect()->route('admin.verify-page.edit')
            ->with('success', 'Verify certificate page content updated successfully.');
    }
}
