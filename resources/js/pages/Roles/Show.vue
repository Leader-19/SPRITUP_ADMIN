<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import roles from '@/routes/roles';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { User } from 'lucide-vue-next';
import { route } from 'ziggy-js';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Roles Show',
        href: '/roles',
    },
];

const props = defineProps({
    "role" : Object,
    "rolePermissions": Array
});




</script>

<template>

    <Head title="Role Show" />


    <AppLayout :breadcrumbs="breadcrumbs">



        <div class="over-flow-x-auto p-3">

            <Link :href="route('roles.index')"
                class="cursor-pointer px-3 py-2 text-xs mb-3 font-medium text-white bg-blue-500 rounded">
                Back
            </Link>

            <div>
                <p><strong>Name</strong>{{ role.name }}</p>
                <p><strong>Permissions</strong>
                    <span
                            v-for="permission in rolePermissions"
                            :key="permission"
                            class="mr-1 bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5"
                            >
                                {{ permission }}
                            </span>
                </p>
            </div>
        </div>
    </AppLayout>
</template>
