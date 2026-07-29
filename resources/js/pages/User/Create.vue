<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { store } from '@/routes/users';
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

            <button type="submit" :disabled="processing" class="rounded bg-black px-4 py-2 text-sm text-white hover:bg-gray-800 disabled:opacity-50">
                Create
            </button>
        </Form>
    </div>
</template>
