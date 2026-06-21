<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { User } from 'lucide-vue-next';
import { route } from 'ziggy-js';
import { can } from '@/lib/can';

interface User {
    permissions: any;
    id: number;
    name: string;
    email: string;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'តួនាទី',
        href: '/roles',
    },
];

const props = defineProps<{
    roles: User[];
}>();

function deleteRole(id) {
    if (confirm("Are you want to delete this role")) {
        router.delete(route('roles.destroy', id));
    }
}

</script>

<template>

    <Head title="តួនាទី" />


    <AppLayout :breadcrumbs="breadcrumbs">



        <div class="over-flow-x-auto p-3">

            <Link v-if="can('roles.create')" href="/roles/create"
                class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-500 rounded">
                បង្កើត​ តួនាទី​ថ្មី
            </Link>

            <table class="w-full mt-3 text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700">
                    <th scope="col" class="px-6 py-3">ល.រ</th>
                    <th scope="col" class="px-6 py-3">ឈ្មោះ</th>
                    <th scope="col" class="px-6 py-3">ការអនុញ្ញាត</th>
                    <th scope="col" class="px-6 py-3">សកម្មភាព</th>
                </thead>

                <tbody>
                    <tr v-for="(role, index) in roles" :key="role.id"
                        class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-900">

                        <td class="px-6 py-2 dark:text-gray-300">{{ index + 1 }}</td>
                        <td class="px-6 py-2 dark:text-gray-300">{{ role.name }}</td>
                        <td class="px-6 py-2 dark:text-gray-300">
                            <!-- Show only first 3 permissions -->
                            <span v-for="(permission, index) in role.permissions.slice(0, 3)"
                                :key="permission.id ?? index"
                                class="mr-1 inline-block bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                {{ permission.name }}
                            </span>

                            <!-- Show +N if more than 3 permissions -->
                            <span v-if="role.permissions.length > 3"
                                class="inline-block bg-gray-200 text-gray-700 text-xs font-medium px-2.5 py-0.5 rounded">
                                +{{ role.permissions.length - 3 }}
                            </span>
                        </td>

                        <td class="px-6 py-2">

                            <Link :href="route('roles.show', role.id)"
                                class="cursor-pointer px-3 py-2 text-xs mr-2 font-medium text-white bg-gray-700 rounded">
                                បង្ហាញ
                            </Link>

                            <Link v-if="can('roles.edit')" :href="route('roles.edit', role.id)"
                                class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-500 rounded">
                                កែរ
                            </Link>
                            <button v-if="can('roles.delete')" @click="deleteRole(role.id)"
                                class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-red-500 rounded ml-2">
                                លុប
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>
