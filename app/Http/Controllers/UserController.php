<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        Gate::authorize('user.view');

        return Inertia::render('User', [
            'users' => User::with('roles')
                ->orderBy('created_at', 'desc')
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    public function create()
    {
        Gate::authorize('user.create');

        return Inertia::render('User/Create', [
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('user.create');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        if (!empty($validated['roles'])) {
            $user->syncRoles(
                Role::whereIn('id', $validated['roles'])->pluck('name')->toArray(),
            );
        }

        return redirect('/users');
    }

    public function edit(string $id)
    {
        Gate::authorize('user.update');

        return Inertia::render('User/Edit', [
            'user' => User::with('roles')->findOrFail($id),
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        Gate::authorize('user.update');

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $user->syncRoles(
            Role::whereIn('id', $validated['roles'] ?? [])->pluck('name')->toArray(),
        );

        return redirect('/users');
    }

    public function destroy(string $id)
    {
        Gate::authorize('user.delete');

        User::findOrFail($id)->delete();

        return redirect('/users');
    }
}
