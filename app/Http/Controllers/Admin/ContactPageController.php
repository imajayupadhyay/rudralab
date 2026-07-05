<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\ContactPageContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ContactPageController extends Controller
{
    public function edit(): Response
    {
        return Inertia::render('Admin/ContactPage/Edit', [
            'content' => ContactPageContent::get(),
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
            'content.info' => ['required', 'array', 'max:8'],
            'content.info.*.icon' => ['nullable', 'string', 'max:80'],
            'content.info.*.label' => ['nullable', 'string', 'max:120'],
            'content.info.*.value' => ['nullable', 'string', 'max:1000'],
            'content.address_card' => ['required', 'array'],
            'content.address_card.mark' => ['nullable', 'string', 'max:10'],
            'content.address_card.label' => ['nullable', 'string', 'max:120'],
            'content.address_card.address' => ['nullable', 'string', 'max:1000'],
            'content.form' => ['required', 'array'],
            'content.form.title' => ['nullable', 'string', 'max:160'],
            'content.form.description' => ['nullable', 'string', 'max:600'],
            'content.form.success_message' => ['nullable', 'string', 'max:600'],
            'content.form.name_label' => ['nullable', 'string', 'max:80'],
            'content.form.name_placeholder' => ['nullable', 'string', 'max:120'],
            'content.form.phone_label' => ['nullable', 'string', 'max:80'],
            'content.form.phone_placeholder' => ['nullable', 'string', 'max:120'],
            'content.form.email_label' => ['nullable', 'string', 'max:80'],
            'content.form.email_placeholder' => ['nullable', 'string', 'max:120'],
            'content.form.message_label' => ['nullable', 'string', 'max:80'],
            'content.form.message_placeholder' => ['nullable', 'string', 'max:240'],
            'content.form.button_text' => ['nullable', 'string', 'max:80'],
            'content.hours' => ['required', 'array'],
            'content.hours.icon' => ['nullable', 'string', 'max:80'],
            'content.hours.label' => ['nullable', 'string', 'max:120'],
            'content.hours.text' => ['nullable', 'string', 'max:240'],
            'content.hours.button_text' => ['nullable', 'string', 'max:120'],
            'content.hours.button_url' => ['nullable', 'string', 'max:255'],
        ])['content'];

        ContactPageContent::save($content);

        return redirect()->route('admin.contact-page.edit')
            ->with('success', 'Contact page content updated successfully.');
    }
}
