<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create Weekly Worklog',
        href: '/weekly-worklogs',
    },
];

const form = useForm({
    worklog_name: "",
    discription: "", // instead of description
    worklog_upload: null as File | null,
});

// handle file change
const handleFileUpload = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.worklog_upload = target.files[0];
    }
};
</script>

<template>
    <Head title="Create Weekly Worklog" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="overflow-x-auto p-3">

            <Link :href="route('weekly-worklogs.index')"
                class="cursor-pointer px-3 py-2 text-xs mb-3 font-medium text-white bg-blue-500 rounded">
                Back
            </Link>

            <form 
              @submit.prevent="form.post(route('weekly-worklogs.store'), { forceFormData: true })"
              class="space-y-6 mt-4 max-w-md mx-auto"
            >

                <!-- Weekly Worklog Name -->
                <div class="grid gap-2">
                    <label class="text-sm font-medium">Weekly Worklog Name</label>

                    <input 
                        type="text"
                        v-model="form.worklog_name"
                        placeholder="Enter the name"
                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 text-base"
                    />

                    <p class="text-red-500 text-sm mt-1" v-if="form.errors.worklog_name">
                        {{ form.errors.worklog_name }}
                    </p>
                </div>

                <!-- File Upload -->
                <div class="grid gap-2">
                    <label class="text-sm font-medium">File Upload</label>

                    <input 
                        type="file"
                        @change="handleFileUpload"
                        class="block w-full text-sm border border-gray-300 rounded-md p-2"
                    />

                    <p class="text-red-500 text-sm mt-1" v-if="form.errors.worklog_upload">
                        {{ form.errors.worklog_upload }}
                    </p>
                </div>

                <!-- Description -->
                <div class="grid gap-2">
                    <label class="text-sm font-medium">Descriptions</label>

                    <input type="text" v-model="form.discription" placeholder="Enter description" />


                    <p class="text-red-500 text-sm mt-1" v-if="form.errors.discription">
                        {{ form.errors.discription }}
                    </p>
                </div>

                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium rounded px-4 py-2">
                    Add Weekly Worklog
                </button>
            </form>
        </div>
    </AppLayout>
</template>
