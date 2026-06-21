<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { User } from 'lucide-vue-next';
import { route } from 'ziggy-js';
import { can } from '@/lib/can';

interface User {
    roles: any;
    id: number;
    name: string;
    email: string;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/users',
    },
];

const props = defineProps<{
    users: User[];
}>();

function deleteUser(id) {
    if (confirm("Are you want to delete this user")){
        router.delete(route('users.destroy', id));
    }
}

</script>

<template>

    <Head title="Users" />


    <AppLayout :breadcrumbs="breadcrumbs">



        <div class="over-flow-x-auto p-3">

            <Link
            v-if="can('users.create')"
            href="/users/create"
                class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-500 rounded">
                Create user 
            </Link>

            <table class="w-full mt-3 text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700">
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">Name</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3">Roles</th>
                    <th scope="col" class="px-6 py-3">Actions</th>
                </thead>

                <tbody>
                    <tr v-for="(user, index) in users" :key="(user.id)"
                        class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-900">

                        <td class="px-6 py-2 dark:text-gray-300">{{ index + 1 }}</td>
                        <td class="px-6 py-2 dark:text-gray-300">{{ user.name }}</td>
                        <td class="px-6 py-2 dark:text-gray-300">{{ user.email }}</td>
                        <td class="px-6 py-2 dark:text-gray-300">
                            <span
                            v-for="role in user.roles"
                            :key="role"
                            class="mr-1 bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5"
                            >
                                {{ role.name }}
                            </span>
                        </td>
                        <td class="px-6 py-2">

                            <Link :href="route('users.show', user.id)"
                                class="cursor-pointer px-3 py-2 text-xs mr-2 font-medium text-white bg-gray-700 rounded">
                                Show
                            </Link> 
                            <Link 
                            v-if="can('users.edit')"
                            :href="route('users.edit', user.id)"
                                
                                class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-500 rounded">
                                Edit
                            </Link>
                            <button
                                v-if="can('users.delete')"
                                @click="deleteUser(user.id)"
                                class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-red-500 rounded ml-2">
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>
