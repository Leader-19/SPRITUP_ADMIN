<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Roles Show',
        href: '/roles',
    },
];

const props = defineProps({
    worklog: Object
});




</script>

<template>

    <Head title="Role Show" />


    <AppLayout :breadcrumbs="breadcrumbs">



        <div class="over-flow-x-auto p-3">

            <Link :href="route('weekly-worklogs.index')"
                class="cursor-pointer px-3 py-2 text-xs mb-3 font-medium text-white bg-blue-500 rounded">
                Back
            </Link>

            <div>
                <p><strong>Name</strong></p>
                <p><strong>WeekLy WorkLog Details</strong>
                    <span v-for="work in worklog" :key="work" class="mr-1 text-xs font-medium px-2.5 py-0.5">
                        {{ work }}
                    </span>
                </p>
            </div>
        </div>
    </AppLayout>
</template>
