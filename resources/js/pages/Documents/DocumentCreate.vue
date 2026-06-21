<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import { type BreadcrumbItem } from '@/types'

// Breadcrumb
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create Document',
        href: '/documents',
    },
]

// Get categories from backend
const page = usePage()
const categories = page.props.categories as any[]

// Form
const form = useForm({
    doc_name: '',
    doc_title: '',
    description: '',
    doc_upload: null as File | null,
    category_id: '',
})

// Handle file upload
const handleFileUpload = (e: Event) => {
    const input = e.target as HTMLInputElement
    if (input.files && input.files.length > 0) {
        form.doc_upload = input.files[0]
    }
}
</script>

<template>
    <Head title="Create Document" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">

            <!-- Back Button -->
            <Link
                :href="route('documents.index')"
                class="inline-block mb-4 px-3 py-2 text-xs font-medium text-white bg-blue-500 rounded"
            >
                Back
            </Link>

            <!-- Form -->
            <form
                @submit.prevent="form.post(route('documents.store'), { forceFormData: true })"
                class="space-y-6 max-w-md mx-auto"
            >

                <!-- Document Name -->
                <div>
                    <label class="block text-sm font-medium">Document Name</label>
                    <input
                        type="text"
                        name="doc_name"
                        v-model="form.doc_name"
                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2"
                        placeholder="Enter document name"
                    />
                    <p v-if="form.errors.doc_name" class="text-red-500 text-sm mt-1">
                        {{ form.errors.doc_name }}
                    </p>
                </div>

                <!-- Document Title -->
                <div>
                    <label class="block text-sm font-medium">Title</label>
                    <input
                        type="text"
                        name="doc_title"
                        v-model="form.doc_title"
                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2"
                        placeholder="Enter title"
                    />
                    <p v-if="form.errors.doc_title" class="text-red-500 text-sm mt-1">
                        {{ form.errors.doc_title }}
                    </p>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium">Category</label>
                    <select
                        name="category_id"
                        v-model="form.category_id"
                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2"
                    >
                        <option value="">Select Category</option>
                        <option
                            v-for="cat in categories"
                            :key="cat.id"
                            :value="cat.id"
                        >
                            {{ cat.title }}
                        </option>
                    </select>

                    <p v-if="form.errors.category_id" class="text-red-500 text-sm mt-1">
                        {{ form.errors.category_id }}
                    </p>
                </div>

                <!-- File Upload -->
                <div>
                    <label class="block text-sm font-medium">Upload File</label>
                    <input
                        type="file"
                        name="doc_upload"
                        @change="handleFileUpload"
                        class="mt-1 block w-full text-sm border border-gray-300 rounded-md p-2"
                    />
                    <p v-if="form.errors.doc_upload" class="text-red-500 text-sm mt-1">
                        {{ form.errors.doc_upload }}
                    </p>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium">Description</label>
                    <input
                        type="text"
                        name="description"
                        v-model="form.description"
                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2"
                        placeholder="Optional description"
                    />
                    <p v-if="form.errors.description" class="text-red-500 text-sm mt-1">
                        {{ form.errors.description }}
                    </p>
                </div>

                <!-- Submit -->
                <button
                    type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 rounded"
                    :disabled="form.processing"
                >
                    Create Document
                </button>

            </form>
        </div>
    </AppLayout>
</template>