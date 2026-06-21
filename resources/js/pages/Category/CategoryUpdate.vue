<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import roles from '@/routes/roles';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { User } from 'lucide-vue-next';
import { route } from 'ziggy-js';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'កែរ​ ប្រភេទ',
        href: '/categories',
    },
];

const props = defineProps({
    "category": Object,
});

const form = useForm({
    "title": props.category.title,
    "description": props.category.description
})


</script>

<template>

    <Head title="កែរ​ ប្រភេទ" />


    <AppLayout :breadcrumbs="breadcrumbs">



        <div class="over-flow-x-auto p-3">

            <Link :href="route('categories.index')"
                class="cursor-pointer px-3 py-2 text-xs mb-3 font-medium text-white bg-blue-500 rounded">
                Back
            </Link>

            <form @submit.prevent="form.put(route('categories.update', category.id))" class="space-y-6 mt-4 max-w-md mx-auto">

                <!-- Name -->
                <div class="grid gap-2">
                    <label for="name" class="text-sm leading-none font-medium select-none peer-disable-cusor">
                        ឈ្មោះ
                    </label>

                    <input type="text" id="name" name="name" v-model="form.title" placeholder="Enter the name"
                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 text-base">
                    <p class="text-red-500 text-sm mt-1" v-if="form.errors.title">{{ form.errors.title }}</p>
                </div>

                <div class="grid gap-1">
                    <label class="text-sm font-medium">រៀបរាប់</label>
                    <input type="text" name="discription" v-model="form.description" placeholder="Enter description"
                        class="block w-full border rounded-md px-3 py-2" />
                    <p class="text-red-500 text-xs" v-if="form.errors.description">
                        {{ form.errors.description }}
                    </p>
                </div>

                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium rounded px-4 py-3">
                    កែរ ប្រភេទ
                </button>
            </form>
        </div>
    </AppLayout>
</template>