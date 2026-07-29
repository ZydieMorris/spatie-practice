<script setup lang="ts">
import { Form, Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { update } from '@/routes/users';

const user = computed(() => usePage().props.user as { id: number; name: string; email: string });
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

            <button type="submit" :disabled="processing" class="rounded bg-black px-4 py-2 text-sm text-white hover:bg-gray-800 disabled:opacity-50">
                Save
            </button>
        </Form>
    </div>
</template>
