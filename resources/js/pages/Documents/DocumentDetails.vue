<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'បង្ហាញ ឯកសារ',
        href: '/documents',
    },
];

const props = defineProps({
    document: Object
});




</script>

<template>

    <Head title="បង្ហាញ ឯកសារ" />


    <AppLayout :breadcrumbs="breadcrumbs">



        <div class="over-flow-x-auto p-3">

            <Link :href="route('documents.index')"
                class="cursor-pointer px-3 py-2 text-xs mb-3 font-medium text-white bg-blue-500 rounded">
                ត្រឡប់ ក្រោយ
            </Link>

            <div>
                <p><strong>ឯកសារ</strong></p>
                <p><strong>បង្ហាញ ឯកសារ</strong>
                    <span v-for="doc in document" :key="doc" class="mr-1 text-xs font-medium px-2.5 py-0.5">
                        {{ doc }}
                    </span>
                </p>
            </div>
        </div>
    </AppLayout>
</template>
