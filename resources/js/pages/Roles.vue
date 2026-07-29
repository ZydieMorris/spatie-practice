<script setup lang="ts">
/**
 * Roles Index Page
 *
 * Displays all roles in a table with Edit and Delete actions.
 * Each role shows its assigned permissions as badges.
 */
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { UserCan } from './lib/UserCan';

const { can } = UserCan();

// ---------------------------------------------------------------------------
// Layout & Breadcrumbs
// ---------------------------------------------------------------------------
defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Roles', href: '/roles' },
        ],
    },
});

// ---------------------------------------------------------------------------
// Page Props
// ---------------------------------------------------------------------------
// The server passes a paginated list of roles with their permissions
interface Role {
    id: number;
    name: string;
    guard_name: string;
    created_at: string;
    permissions: { id: number; name: string }[];
}

interface RolesProp {
    data: Role[];
}

const roles = computed<RolesProp>(
    () => (usePage().props.roles ?? { data: [] }) as RolesProp,
);

// ---------------------------------------------------------------------------
// Actions
// ---------------------------------------------------------------------------
function deleteRole(role: Role): void {
    if (confirm(`Delete role "${role.name}"? This cannot be undone.`)) {
        router.delete(`/roles/${role.id}`);
    }
}
</script>

<template>
    <Head title="Roles" />

    <div class="flex flex-1 flex-col gap-4 p-4">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Roles</h1>
                <p class="text-sm text-gray-500">
                    Manage user roles and their permissions
                </p>
            </div>
            <Link
                v-if="can('role.create')"
                href="/roles/create"
                class="rounded bg-black px-4 py-2 text-sm text-white hover:bg-gray-800"
            >
                Add Role
            </Link>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded border">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="px-4 py-3">ID</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Permissions</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="role in roles.data"
                        :key="role.id"
                        class="border-b last:border-0 hover:bg-gray-50"
                    >
                        <td class="px-4 py-3">{{ role.id }}</td>
                        <td class="px-4 py-3 font-medium">{{ role.name }}</td>

                        <!-- Permission badges -->
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                <span
                                    v-for="perm in role.permissions"
                                    :key="perm.id"
                                    class="inline-block rounded bg-gray-100 px-2 py-0.5 text-xs text-gray-700"
                                >
                                    {{ perm.name }}
                                </span>
                                <span
                                    v-if="!role.permissions.length"
                                    class="text-xs text-gray-400"
                                >
                                    No permissions
                                </span>
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="px-4 py-3 text-right">
                            <Link
                                v-if="can('role.update')"
                                :href="`/roles/${role.id}/edit`"
                                class="mr-2 text-sm text-blue-600 hover:underline"
                            >
                                Edit
                            </Link>
                            <button
                                v-if="can('role.delete')"
                                class="text-sm text-red-600 hover:underline"
                                @click="deleteRole(role)"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>

                    <!-- Empty state -->
                    <tr v-if="!roles.data.length">
                        <td
                            colspan="4"
                            class="px-4 py-8 text-center text-gray-500"
                        >
                            No roles found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
