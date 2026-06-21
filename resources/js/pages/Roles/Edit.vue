<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import roles from '@/routes/roles';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { User } from 'lucide-vue-next';
import { route } from 'ziggy-js';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Roles Edit',
        href: '/roles',
    },
];

const props = defineProps({
    "role" : Object,
    "permissions": Array,
    "rolePermissions": Array
});

const form = useForm({
    "name": props.role.name,
    "permissions": props.rolePermissions || []
})


</script>

<template>

    <Head title="Role Edit" />


    <AppLayout :breadcrumbs="breadcrumbs">



        <div class="over-flow-x-auto p-3">

            <Link :href="route('roles.index')"
                class="cursor-pointer px-3 py-2 text-xs mb-3 font-medium text-white bg-blue-500 rounded">
                Back
            </Link>

            <form @submit.prevent="form.put(route('roles.update', role.id))" class="space-y-6 mt-4 max-w-md mx-auto">

                <!-- Name -->
                <div class="grid gap-2">
                    <label for="name" class="text-sm leading-none font-medium select-none peer-disable-cusor">
                        Name
                    </label>

                    <input type="text" id="name" name="name" v-model="form.name" placeholder="Enter the name"
                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 text-base">
                    <p class="text-red-500 text-sm mt-1" v-if="form.errors.name">{{ form.errors.name }}</p>
                </div>

                <!-- permission -->

                <div class="grid gap-2">
                    <label for="name" class="text-sm leading-none font-medium select-none peer-disable-cusor">
                        Permission
                    </label>

                    <label v-for="permission in permissions" class="flex items-center space-x-2">
                        <input v-model="form.permissions" :value="permission" type="checkbox"
                            class="form-checkbox h-5 w-5 text-blue-600 focus:ring-2">
                        <span class="text-gray-500 capitalize">{{ permission }}</span>
                    </label>
                    <p class="text-red-500 text-sm mt-1" v-if="form.errors.permissions">{{ form.errors.permissions }}
                    </p>
                </div>

                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium rounded px-4 py-3">
                    Create New Role
                </button>
            </form>
        </div>
    </AppLayout>
</template>
