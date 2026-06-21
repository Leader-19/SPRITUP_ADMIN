<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { can } from '@/lib/can';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Update Weekly Worklog',
        href: '/weekly-worklogs',
    },
];

const props = defineProps({
    worklog: Object,
    score: Object
});

// Initialize form (important to include _method)
const form = useForm({
    _method: "PUT",
    worklog_name: props.worklog?.worklog_name ?? "",
    discription: props.worklog?.discription ?? "",
    worklog_upload: null as File | null,
    weekly_worklog_score: props.score?.weekly_worklog_score ?? null,
});

// Handle file input
const handleFileUpload = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.worklog_upload = target.files[0];
    }
};

// Submit update using form.post() + _method
const submit = () => {
    form.post(route('weekly-worklogs.update', props.worklog.id), {
        forceFormData: true,
        onSuccess: () => console.log("Updated successfully"),
        onError: (err) => console.log("Validation errors:", err),
    });
};
</script>

<template>

    <Head title="Update Weekly Worklog" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-3">
            <Link :href="route('weekly-worklogs.index')" class="px-3 py-2 text-xs text-white bg-blue-500 rounded">
                Back
            </Link>

            <form @submit.prevent="submit" class="space-y-5 mt-4 max-w-md mx-auto">

                <!-- Name -->
                <div class="grid gap-1">
                    <label class="text-sm font-medium">Weekly Worklog Name</label>
                    <input type="text" name="worklog_name" v-model="form.worklog_name" placeholder="Enter the name"
                        class="block w-full border rounded-md px-3 py-2" />
                    <p class="text-red-500 text-xs" v-if="form.errors.worklog_name">
                        {{ form.errors.worklog_name }}
                    </p>
                </div>

                <!-- File -->
                <div class="grid gap-1">
                    <label class="text-sm font-medium">File Upload</label>
                    <input type="file" @change="handleFileUpload" class="block w-full border rounded-md p-2 text-xs" />
                    <p class="text-red-500 text-xs" v-if="form.errors.worklog_upload">
                        {{ form.errors.worklog_upload }}
                    </p>

                    <p v-if="props.worklog?.worklog_upload" class="text-xs text-gray-500">
                        Current file:
                        <a :href="`/storage/${props.worklog.worklog_upload}`" target="_blank"
                            class="text-blue-500 underline">
                            View
                        </a>
                    </p>
                </div>

                <!-- Description -->
                <div class="grid gap-1">
                    <label class="text-sm font-medium">Description</label>
                    <input type="text" name="discription" v-model="form.discription" placeholder="Enter description"
                        class="block w-full border rounded-md px-3 py-2" />
                    <p class="text-red-500 text-xs" v-if="form.errors.discription">
                        {{ form.errors.discription }}
                    </p>
                </div>


                <!-- Score -->
                <div 
                v-if="can('score.create')"
                class="grid gap-1">
                    <label class="text-sm font-medium">Weekly Worklog Score</label>
                    <input type="number" min="0" max="100" name="weekly_worklog_score"
                        v-model.number="form.weekly_worklog_score" placeholder="Enter score"
                        class="block w-full border rounded-md px-3 py-2" />
                    <p class="text-red-500 text-xs" v-if="form.errors.weekly_worklog_score">
                        {{ form.errors.weekly_worklog_score }}
                    </p>
                </div>

                <button type="submit" class="bg-green-600 text-white text-xs rounded px-4 py-2">
                    Update Weekly Worklog
                </button>
            </form>
        </div>
    </AppLayout>
</template>