<script setup lang="ts">
import { Form, Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { store } from '@/routes/users';

interface Role {
    id: number;
    name: string;
}

const roles = computed<Role[]>(
    () => (usePage().props.roles ?? []) as Role[],
);
</script>

<template>
    <Head title="Create User" />

    <div class="flex flex-1 flex-col gap-4 p-4">
        <Link href="/users" class="text-sm text-blue-600 hover:underline">&larr; Back to Users</Link>

        <h1 class="text-2xl font-bold">Create User</h1>

        <Form v-bind="store.form()" class="flex max-w-md flex-col gap-4" v-slot="{ errors, processing }">
            <div>
                <label class="mb-1 block text-sm font-medium">Name</label>
                <input name="name" required class="w-full rounded border px-3 py-2 text-sm" />
                <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium">Email</label>
                <input name="email" type="email" required class="w-full rounded border px-3 py-2 text-sm" />
                <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium">Password</label>
                <input name="password" type="password" required class="w-full rounded border px-3 py-2 text-sm" />
                <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password }}</p>
            </div>

            <div>
                <h2 class="mb-2 text-sm font-medium">Roles</h2>
                <p class="mb-3 text-xs text-gray-500">Select the roles to assign to this user.</p>

                <div class="space-y-1 rounded border p-3">
                    <label
                        v-for="role in roles"
                        :key="role.id"
                        class="flex cursor-pointer items-center gap-2 text-sm"
                    >
                        <input type="checkbox" name="roles[]" :value="role.id" />
                        {{ role.name }}
                    </label>

                    <p v-if="!roles.length" class="text-xs text-gray-400">
                        No roles available. Create roles first.
                    </p>
                </div>

                <p v-if="errors.roles" class="mt-1 text-sm text-red-600">{{ errors.roles }}</p>
            </div>

            <button type="submit" :disabled="processing" class="rounded bg-black px-4 py-2 text-sm text-white hover:bg-gray-800 disabled:opacity-50">
                Create
            </button>
        </Form>
    </div>
</template>
