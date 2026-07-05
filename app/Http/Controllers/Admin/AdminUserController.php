<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class AdminUserController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim((string) $request->query('search', ''));

        $users = User::query()
            ->where('is_admin', true)
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString()
            ->through(fn (User $user): array => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at?->format('d M Y, h:i A'),
                'created_at' => $user->created_at?->format('d M Y'),
                'is_current_user' => $request->user()->is($user),
            ]);

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'filters' => [
                'search' => $search,
            ],
            'adminCount' => User::where('is_admin', true)->count(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Users/Create', [
            'adminUser' => $this->emptyFormData(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateUser($request);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Admin user created successfully.');
    }

    public function edit(User $user): Response
    {
        $this->ensureAdminUser($user);

        return Inertia::render('Admin/Users/Edit', [
            'adminUser' => $this->formData($user),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->ensureAdminUser($user);

        $data = $this->validateUser($request, $user);

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'is_admin' => true,
            'email_verified_at' => $user->email === $data['email']
                ? $user->email_verified_at
                : now(),
        ];

        if (! empty($data['password'])) {
            $payload['password'] = $data['password'];
        }

        $user->update($payload);

        return redirect()->route('admin.users.index')
            ->with('success', 'Admin user updated successfully.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $this->ensureAdminUser($user);

        if ($request->user()->is($user)) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own admin account while logged in.');
        }

        if (User::where('is_admin', true)->count() <= 1) {
            return redirect()->route('admin.users.index')
                ->with('error', 'At least one admin account must remain.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Admin user deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateUser(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'email' => [
                'required',
                'email',
                'max:160',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'password' => [
                $user ? 'nullable' : 'required',
                'string',
                'min:8',
                'max:160',
                'confirmed',
            ],
        ]);
    }

    private function ensureAdminUser(User $user): void
    {
        abort_unless($user->is_admin, 404);
    }

    /**
     * @return array<string, mixed>
     */
    private function emptyFormData(): array
    {
        return [
            'id' => null,
            'name' => '',
            'email' => '',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at?->format('d M Y, h:i A'),
            'email_verified_at' => $user->email_verified_at?->format('d M Y, h:i A'),
        ];
    }
}
