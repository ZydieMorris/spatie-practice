<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        Gate::authorize('role.view');

        $roles = Role::with('permissions')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Roles', [
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        Gate::authorize('role.create');

        $permissions = Permission::orderBy('name')->get();

        return Inertia::render('Roles/Create', [
            'permissions' => $permissions,
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('role.create');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role = Role::create(['name' => $validated['name']]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions(
                Permission::whereIn('id', $validated['permissions'])->pluck('name')->toArray(),
            );
        }

        return redirect('/roles');
    }

    public function edit(string $id)
    {
        Gate::authorize('role.update');

        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::orderBy('name')->get();

        return Inertia::render('Roles/Edit', [
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }

    public function update(Request $request, string $id)
    {
        Gate::authorize('role.update');

        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,' . $id],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions(
            Permission::whereIn('id', $validated['permissions'] ?? [])->pluck('name')->toArray(),
        );

        return redirect('/roles');
    }

    public function destroy(string $id)
    {
        Gate::authorize('role.delete');

        Role::findOrFail($id)->delete();

        return redirect('/roles');
    }
}
