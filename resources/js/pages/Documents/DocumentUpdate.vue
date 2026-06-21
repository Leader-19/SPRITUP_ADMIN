<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Update Document',
        href: route('documents.index'), // Correct route
    },
];

// Props from Laravel/Inertia
const props = defineProps<{
    document: {
        id: number;
        doc_name: string;
        doc_title: string;
        description: string | null;
        doc_upload?: string | null;
        category_id?: number | null; // nullable category
    };
    categories: { id: number; title: string }[]; // list of categories
}>();

// Initialize Inertia form
const form = useForm({
    _method: 'PUT',
    doc_name: props.document.doc_name ?? '',
    doc_title: props.document.doc_title ?? '',
    description: props.document.description ?? '',
    doc_upload: null as File | null,
    category_id: props.document.category_id ?? null,
});

// Handle file input
const handleFileUpload = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.doc_upload = target.files[0];
    }
};

// Submit form
const submit = () => {
    form.post(route('documents.update', props.document.id), {
        forceFormData: true,
        onSuccess: () => console.log('Updated successfully'),
        onError: (err) => console.log('Validation errors:', err),
    });
};
</script>

<template>

    <Head title="Update Document" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-3 max-w-lg mx-auto">
            <!-- Back button -->
            <Link :href="route('documents.index')" class="px-3 py-2 text-xs text-white bg-blue-500 rounded">
                Back
            </Link>

            <!-- Update form -->
            <form @submit.prevent="submit" class="space-y-5 mt-4">

                <!-- Document Name -->
                <div class="grid gap-1">
                    <label class="text-sm font-medium">ឈ្មោះឯកសារ</label>
                    <input type="text" name="doc_name" v-model="form.doc_name" placeholder="Enter document name"
                        class="block w-full border rounded-md px-3 py-2" />
                    <p class="text-red-500 text-xs" v-if="form.errors.doc_name">
                        {{ form.errors.doc_name }}
                    </p>
                </div>

                <!-- Document Title -->
                <div class="grid gap-1">
                    <label class="text-sm font-medium">ចំណងជើង</label>
                    <input type="text" name="doc_title" v-model="form.doc_title" placeholder="Enter document title"
                        class="block w-full border rounded-md px-3 py-2" />
                    <p class="text-red-500 text-xs" v-if="form.errors.doc_title">
                        {{ form.errors.doc_title }}
                    </p>
                </div>

                <!-- Category (nullable) -->
                <div class="grid gap-1">
                    <label class="text-sm font-medium">Category (optional)</label>
                    <select v-model="form.category_id" name="category_id"
                        class="block w-full border rounded-md px-3 py-2">
                        <option :value="null">-- No category --</option>
                        <option v-for="cat in props.categories" :key="cat.id" :value="Number(cat.id)" 
                            >
                            {{ cat.title }}
                        </option>
                    </select>
                    <p class="text-red-500 text-xs" v-if="form.errors.category_id">
                        {{ form.errors.category_id }}
                    </p>
                </div>

                <!-- File Upload -->
                <div class="grid gap-1">
                    <label class="text-sm font-medium">File Upload</label>
                    <input type="file" name="doc_upload" @change="handleFileUpload"
                        class="block w-full border rounded-md p-2 text-xs" />
                    <p class="text-red-500 text-xs" v-if="form.errors.doc_upload">
                        {{ form.errors.doc_upload }}
                    </p>

                    <p v-if="props.document.doc_upload" class="text-xs text-gray-500">
                        Current file:
                        <a :href="`/storage/${props.document.doc_upload}`" target="_blank"
                            class="text-blue-500 underline">
                            View
                        </a>
                    </p>
                </div>

                <!-- Description -->
                <div class="grid gap-1">
                    <label class="text-sm font-medium">ការរៀបរាប់</label>
                    <input type="text" name="description" v-model="form.description" placeholder="Enter description"
                        class="block w-full border rounded-md px-3 py-2" />
                    <p class="text-red-500 text-xs" v-if="form.errors.description">
                        {{ form.errors.description }}
                    </p>
                </div>

                <!-- Submit -->
                <button type="submit" class="bg-green-600 text-white text-xs rounded px-4 py-2">
                    Update Document
                </button>
            </form>
        </div>
    </AppLayout>
</template>