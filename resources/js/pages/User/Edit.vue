<script setup lang="ts">
import { Form, Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { update } from '@/routes/users';

interface Role {
    id: number;
    name: string;
}

interface UserData {
    id: number;
    name: string;
    email: string;
    roles: { id: number; name: string }[];
}

const page = usePage();

const user = computed<UserData>(() => page.props.user as UserData);
const roles = computed<Role[]>(
    () => (page.props.roles ?? []) as Role[],
);

function hasRole(roleId: number): boolean {
    return user.value.roles.some((r) => r.id === roleId);
}
</script>

<template>
    <Head title="Edit User" />

    <div class="flex flex-1 flex-col gap-4 p-4">
        <Link href="/users" class="text-sm text-blue-600 hover:underline">&larr; Back to Users</Link>

        <h1 class="text-2xl font-bold">Edit User</h1>

        <Form v-bind="update.form(user.id)" class="flex max-w-md flex-col gap-4" v-slot="{ errors, processing }">
            <div>
                <label class="mb-1 block text-sm font-medium">Name</label>
                <input name="name" :default-value="user.name" required class="w-full rounded border px-3 py-2 text-sm" />
                <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium">Email</label>
                <input name="email" type="email" :default-value="user.email" required class="w-full rounded border px-3 py-2 text-sm" />
                <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
            </div>

            <div>
                <h2 class="mb-2 text-sm font-medium">Roles</h2>
                <p class="mb-3 text-xs text-gray-500">Update the roles assigned to this user.</p>

                <div class="space-y-1 rounded border p-3">
                    <label
                        v-for="role in roles"
                        :key="role.id"
                        class="flex cursor-pointer items-center gap-2 text-sm"
                    >
                        <input
                            type="checkbox"
                            name="roles[]"
                            :value="role.id"
                            :checked="hasRole(role.id)"
                        />
                        {{ role.name }}
                    </label>

                    <p v-if="!roles.length" class="text-xs text-gray-400">
                        No roles available.
                    </p>
                </div>

                <p v-if="errors.roles" class="mt-1 text-sm text-red-600">{{ errors.roles }}</p>
            </div>

            <button type="submit" :disabled="processing" class="rounded bg-black px-4 py-2 text-sm text-white hover:bg-gray-800 disabled:opacity-50">
                Save
            </button>
        </Form>
    </div>
</template>
