<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\FooterContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FooterController extends Controller
{
    public function edit(): Response
    {
        return Inertia::render('Admin/Footer/Edit', [
            'content' => FooterContent::get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $content = $request->validate([
            'content' => ['required', 'array'],
            'content.brand' => ['required', 'array'],
            'content.brand.mark' => ['nullable', 'string', 'max:10'],
            'content.brand.title' => ['nullable', 'string', 'max:180'],
            'content.brand.description' => ['nullable', 'string', 'max:600'],
            'content.navigation' => ['required', 'array'],
            'content.navigation.heading' => ['nullable', 'string', 'max:80'],
            'content.navigation.links' => ['required', 'array', 'max:8'],
            'content.navigation.links.*.label' => ['nullable', 'string', 'max:80'],
            'content.navigation.links.*.url' => ['nullable', 'string', 'max:255', 'regex:/^(\/|#)/'],
            'content.services' => ['required', 'array'],
            'content.services.heading' => ['nullable', 'string', 'max:80'],
            'content.services.items' => ['required', 'array', 'max:12'],
            'content.services.items.*.label' => ['nullable', 'string', 'max:140'],
            'content.legal' => ['required', 'array'],
            'content.legal.copyright' => ['nullable', 'string', 'max:220'],
            'content.legal.tagline' => ['nullable', 'string', 'max:180'],
        ])['content'];

        FooterContent::save($content);

        return redirect()->route('admin.footer.edit')
            ->with('success', 'Footer content updated successfully.');
    }
}
