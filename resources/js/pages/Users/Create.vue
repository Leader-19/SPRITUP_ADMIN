<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { User } from 'lucide-vue-next';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users Create',
        href: '/users',
    },
];

defineProps ({
    "roles" : Array
})

const form = useForm({
    "name": "",
    "email": "",
    "password": "",
    "roles" : []
})


</script>

<template>

    <Head title="Users Create" />


    <AppLayout :breadcrumbs="breadcrumbs">



        <div class="over-flow-x-auto p-3">

            <Link href="/users"
                class="cursor-pointer px-3 py-2 text-xs mb-3 font-medium text-white bg-blue-500 rounded">
                Back
            </Link>

            <form @submit.prevent="form.post('/users')" class="space-y-6 mt-4 max-w-md mx-auto">

                <!-- Name -->
                <div class="grid gap-2">
                    <label for="name" class="text-sm leading-none font-medium select-none peer-disable-cusor">
                        Name
                    </label>

                    <input type="text" id="name" name="name" v-model="form.name" placeholder="Enter the name"
                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 text-base">
                    <p class="text-red-500 text-sm mt-1" v-if="form.errors.name">{{ form.errors.name }}</p>
                </div>

                <!-- Email -->
                <div class="grid gap-2">
                    <label for="email" class="text-sm leading-none font-medium select-none">
                        Email
                    </label>

                    <input type="email" id="email" name="email" v-model="form.email" placeholder="Enter the email"
                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 text-base">
                    <p class="text-red-500 text-sm mt-1" v-if="form.errors.email">{{ form.errors.email }}</p>
                </div>

                <!-- Password -->
                <div class="grid gap-2">
                    <label for="password" class="text-sm leading-none font-medium select-none">
                        Password
                    </label>

                    <input type="password" id="password" name="password" v-model="form.password"
                        placeholder="Enter the password"
                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 text-base">
                    <p class="text-red-500 text-sm mt-1" v-if="form.errors.password">{{ form.errors.password }}</p>
                </div>

                <div class="grid gap-2">
                    <label for="name" class="text-sm leading-none font-medium select-none peer-disable-cusor">
                        Roles:
                    </label>

                    <label v-for="role in roles" class="flex items-center space-x-2">
                        <input v-model="form.roles" :value="role" type="checkbox"
                            class="form-checkbox h-5 w-5 text-blue-600 focus:ring-2">
                        <span class="text-gray-500 capitalize">{{ role }}</span>
                    </label>
                    <p class="text-red-500 text-sm mt-1" v-if="form.errors.roles">{{ form.errors.roles }}
                    </p>
                </div>

                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium rounded px-4 py-2">
                    Create
                </button>
            </form>
        </div>
    </AppLayout>
</template>
