<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import roles from '@/routes/roles';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { User } from 'lucide-vue-next';
import { route } from 'ziggy-js';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'បង្ហាញប្រភេទ',
        href: '/categories',
    },
];

const props = defineProps({
    "category" : Object,
});




</script>

<template>

    <Head title="បង្ហាញប្រភេទ" />


    <AppLayout :breadcrumbs="breadcrumbs">



        <div class="over-flow-x-auto p-3">

            <Link :href="route('categories.index')"
                class="cursor-pointer px-3 py-2 text-xs mb-3 font-medium text-white bg-blue-500 rounded">
                ត្រឡប់ក្រោយ
            </Link>

            <div>
                <p><strong>ឈ្មោះ</strong>{{ category.title }}</p>
                <p><strong>ការរៀបរាប់</strong>{{ category.description }}</p>
               
            </div>
        </div>
    </AppLayout>
</template>
