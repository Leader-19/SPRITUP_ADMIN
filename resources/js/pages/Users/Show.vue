<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { email } from '@/routes/password';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, useForm } from '@inertiajs/vue3';

import { route } from 'ziggy-js';



const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users Details',
        href: '/users',
    },
];


interface User {
  name: string;
  email: string;
}

const props = defineProps<{
  user: User;
}>();




</script>

<template>

    <Head title="Users Details" />


    <AppLayout :breadcrumbs="breadcrumbs">



        <div class="over-flow-x-auto p-3">

            <Link href="/users"
                class="cursor-pointer px-3 py-2 text-xs mb-3 font-medium text-white bg-blue-500 rounded">
                Back
            </Link>

            <div>
                <p><strong>Name:</strong> {{ user.name }}</p>
                <p><strong>Email</strong>{{ user.email }}</p>
            </div>
        </div>
    </AppLayout>
</template>
