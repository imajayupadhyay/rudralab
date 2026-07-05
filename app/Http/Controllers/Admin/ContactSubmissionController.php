<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ContactSubmissionController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim((string) $request->query('search', ''));
        $status = (string) $request->query('status', '');

        $submissions = ContactSubmission::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('message', 'like', "%{$search}%");
                });
            })
            ->when(in_array($status, ['new', 'read'], true), fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(12)
            ->withQueryString()
            ->through(fn (ContactSubmission $submission): array => [
                'id' => $submission->id,
                'name' => $submission->name,
                'phone' => $submission->phone,
                'email' => $submission->email,
                'message' => str($submission->message)->limit(120)->toString(),
                'status' => $submission->status,
                'created_at' => $submission->created_at?->format('d M Y, h:i A'),
            ]);

        return Inertia::render('Admin/ContactSubmissions/Index', [
            'submissions' => $submissions,
            'filters' => [
                'search' => $search,
                'status' => $status,
            ],
        ]);
    }

    public function show(ContactSubmission $contactSubmission): Response
    {
        $contactSubmission->markRead();

        return Inertia::render('Admin/ContactSubmissions/Show', [
            'submission' => [
                'id' => $contactSubmission->id,
                'name' => $contactSubmission->name,
                'phone' => $contactSubmission->phone,
                'email' => $contactSubmission->email,
                'message' => $contactSubmission->message,
                'status' => $contactSubmission->status,
                'ip_address' => $contactSubmission->ip_address,
                'user_agent' => $contactSubmission->user_agent,
                'created_at' => $contactSubmission->created_at?->format('d M Y, h:i A'),
                'read_at' => $contactSubmission->read_at?->format('d M Y, h:i A'),
            ],
        ]);
    }

    public function destroy(ContactSubmission $contactSubmission): RedirectResponse
    {
        $contactSubmission->delete();

        return redirect()->route('admin.contact-submissions.index')
            ->with('success', 'Contact submission deleted successfully.');
    }
}
