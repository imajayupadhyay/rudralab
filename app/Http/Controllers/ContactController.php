<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use App\Support\ContactPageContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Contact/index', [
            'content' => ContactPageContent::get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'phone' => ['nullable', 'string', 'max:80'],
            'email' => ['nullable', 'email', 'max:160'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        ContactSubmission::create([
            ...$data,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
        ]);

        return redirect()->route('contact')
            ->with('success', 'Your message has been submitted successfully.');
    }
}
