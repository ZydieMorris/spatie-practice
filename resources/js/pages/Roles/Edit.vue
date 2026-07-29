<script setup lang="ts">
/**
 * Roles - Edit Page
 *
 * Form to update a role's name and its assigned permissions.
 * The checkboxes are pre-checked based on the role's current permissions.
 */
import { Form, Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

// ---------------------------------------------------------------------------
// Page Props
// ---------------------------------------------------------------------------
interface Permission {
    id: number;
    name: string;
}

interface Role {
    id: number;
    name: string;
    permissions: { id: number; name: string }[];
}

// Get the role and all available permissions from the server
const page = usePage();

const role = computed<Role>(() => page.props.role as Role);
const permissions = computed<Permission[]>(
    () => (page.props.permissions ?? []) as Permission[],
);

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------
// Check if the role already has a given permission (for pre-checking)
function hasPermission(permissionId: number): boolean {
    return role.value.permissions.some((p) => p.id === permissionId);
}

// Group permissions by their prefix (e.g. "user.view" -> "user")
function getPermissionGroup(name: string): string {
    return name.split('.')[0] ?? 'other';
}

// Get a display label for a permission group
function getGroupLabel(group: string): string {
    return group.charAt(0).toUpperCase() + group.slice(1);
}

// Group the flat permissions array by prefix for organized display
const groupedPermissions = computed(() => {
    const groups: Record<string, Permission[]> = {};

    for (const perm of permissions.value) {
        const group = getPermissionGroup(perm.name);

        if (!groups[group]) {
            groups[group] = [];
        }

        groups[group].push(perm);
    }

    return groups;
});

// Build the form action URL with the PUT method override
const formAction = computed(() => `/roles/${role.value.id}`);
</script>

<template>
    <Head title="Edit Role" />

    <div class="flex flex-1 flex-col gap-4 p-4">
        <Link href="/roles" class="text-sm text-blue-600 hover:underline">
            &larr; Back to Roles
        </Link>

        <h1 class="text-2xl font-bold">Edit Role</h1>

        <Form
            :action="formAction"
            method="post"
            class="flex max-w-lg flex-col gap-6"
            v-slot="{ errors, processing }"
        >
            <!-- Laravel uses _method to spoof PUT requests -->
            <input type="hidden" name="_method" value="PUT" />

            <!-- Role Name -->
            <div>
                <label for="name" class="mb-1 block text-sm font-medium">
                    Role Name
                </label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    required
                    :default-value="role.name"
                    placeholder="e.g. admin, editor"
                    class="w-full rounded border px-3 py-2 text-sm"
                />
                <p v-if="errors.name" class="mt-1 text-sm text-red-600">
                    {{ errors.name }}
                </p>
            </div>

            <!-- Permissions Section -->
            <div>
                <h2 class="mb-2 text-sm font-medium">Permissions</h2>
                <p class="mb-3 text-xs text-gray-500">
                    Update the permissions assigned to this role.
                </p>

                <div class="space-y-4">
                    <!-- Loop through each group (e.g. "user", "role") -->
                    <fieldset
                        v-for="(perms, group) in groupedPermissions"
                        :key="group"
                        class="rounded border p-3"
                    >
                        <legend class="px-1 text-sm font-medium">
                            {{ getGroupLabel(group) }}
                        </legend>

                        <div class="mt-2 space-y-1">
                            <!-- Loop through each permission in the group -->
                            <label
                                v-for="perm in perms"
                                :key="perm.id"
                                class="flex cursor-pointer items-center gap-2 text-sm"
                            >
                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    :value="perm.id"
                                    :checked="hasPermission(perm.id)"
                                />
                                {{ perm.name }}
                            </label>
                        </div>
                    </fieldset>
                </div>

                <p v-if="errors.permissions" class="mt-1 text-sm text-red-600">
                    {{ errors.permissions }}
                </p>
            </div>

            <button
                type="submit"
                :disabled="processing"
                class="self-start rounded bg-black px-4 py-2 text-sm text-white hover:bg-gray-800 disabled:opacity-50"
            >
                Save Changes
            </button>
        </Form>
    </div>
</template>
