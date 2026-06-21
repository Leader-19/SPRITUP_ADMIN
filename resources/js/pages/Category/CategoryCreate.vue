<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import { type BreadcrumbItem } from '@/types'


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'បង្កើត ប្រភេទថ្មី',
        href: '/categories',
    },
]

const form = useForm({
    title: '',
    description: '', // keep spelling if DB uses this
})

</script>


<template>
    <Head title="បង្កើត ប្រភេទថ្មី" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">

            <!-- Back Button -->
            <Link
                :href="route('categories.index')"
                class="inline-block mb-4 px-3 py-2 text-xs font-medium text-white bg-blue-500 rounded"
            >
                ត្រឡប់ក្រោយ
            </Link>

            <!-- Form -->
            <form
                @submit.prevent="form.post(route('categories.store'), { forceFormData: true })"
                class="space-y-6 max-w-md mx-auto"
            >
                <!-- Report Name -->
                <div>
                    <label class="block text-sm font-medium">ឈ្មោះប្រភេទ</label>
                    <input
                        type="text"
                        v-model="form.title"
                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2"
                        placeholder="ដាក់ឈ្មោះប្រភេទ"
                    />
                    <p v-if="form.errors.title" class="text-red-500 text-sm mt-1">
                        {{ form.errors.title }}
                    </p>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium">រៀបរាប់</label>
                    <input
                        type="text"
                        v-model="form.description"
                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2"
                        placeholder="រៀបរាប់"
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
                    បង្កើត​ ប្រភេទថ្មី
                </button>
            </form>
        </div>
    </AppLayout>
</template>
