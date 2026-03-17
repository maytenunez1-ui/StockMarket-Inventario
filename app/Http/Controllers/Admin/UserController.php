<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->withCount('loans')
            ->latest()
            ->paginate(12);

        $adminCount = User::where('role', 'admin')->count();

        return view('admin.users.index', compact('users', 'adminCount'));
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(['admin', 'user'])],
        ]);

        $isRemovingAdminRole = $user->role === 'admin' && $validated['role'] !== 'admin';

        if ($isRemovingAdminRole && User::where('role', 'admin')->count() === 1) {
            return back()->with('error', 'Debe existir al menos un administrador en el sistema.');
        }

        $user->update(['role' => $validated['role']]);

        return back()->with('success', 'Rol de usuario actualizado correctamente.');
    }
}
