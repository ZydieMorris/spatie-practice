<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Users', href: '/users' },
        ],
    },
});

const users = computed(() => (usePage().props.users ?? { data: [] }) as { data: { id: number; name: string; email: string }[] });
</script>

<template>
    <Head title="Users" />

    <div class="flex flex-1 flex-col gap-4 p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Users</h1>
            <Link href="/users/create" class="rounded bg-black px-4 py-2 text-sm text-white hover:bg-gray-800">Add User</Link>
        </div>

        <div class="overflow-x-auto rounded border">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="px-4 py-3">ID</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users.data" :key="user.id" class="border-b last:border-0     ">
                        <td class="px-4 py-3">{{ user.id }}</td>
                        <td class="px-4 py-3 font-medium">{{ user.name }}</td>
                        <td class="px-4 py-3">{{ user.email }}</td>
                        <td class="px-4 py-3 text-right">
                            <Link :href="`/users/${user.id}/edit`" class="mr-2 text-sm text-blue-600 hover:underline">Edit</Link>
                            <button class="text-sm text-red-600 hover:underline" @click="router.delete(`/users/${user.id}`)">Delete</button>
                        </td>
                    </tr>
                    <tr v-if="!users.data.length">
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">No users found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
