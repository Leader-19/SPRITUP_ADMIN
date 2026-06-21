<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import {
    ClipboardCheck,
    FileText,
    Plus,
    Pencil,
    Trash2
} from 'lucide-vue-next';
import { can } from '@/lib/can';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'WeekLy WorkLog',
        href: '/weekly-worklogs',
    },
];

const props = defineProps<{
    weeklyWorklogs: Array<{
        id: number;
        worklog_name: string;
        worklog_upload: string;
        discription: string;
        created_at: string;
        updated_at: string;
        user: {
            id: number;
            name: string;
            email: string;
        };
        score: {
            id: number;
            weekly_worklog_score: number
        }
    }>;
}>();

function deleteWeeklyWorklog(id) {
    if (confirm("Are you want to delete this worklog")) {
        router.delete(route('weekly-worklogs.destroy', id));
    }
}


</script>

<template>

    <Head title="Weekly Worklog" />


    <AppLayout :breadcrumbs="breadcrumbs">



        <div class="over-flow-x-auto p-3">

            <Link :href="route('weekly-worklogs.create')" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold
                           text-white bg-blue-600 rounded-md hover:bg-blue-700 transition">
                <Plus class="w-4 h-4" />
                Create Worklog
            </Link>

            <table class="w-full mt-3 text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700">
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th v-if="can('score.create')" scope="col" class="px-6 py-3">Students</th>
                    <th scope="col" class="px-6 py-3">WeekLy Reports</th>
                    <th scope="col" class="px-6 py-3">Description</th>
                    <th scope="col" class="px-6 py-3">Files</th>

                    <th scope="col" class="px-6 py-3">Score</th>
                    <th scope="col" class="px-6 py-3 text-center">Actions</th>
                </thead>

                <tbody>
                    <tr v-for="(worklog, index) in props.weeklyWorklogs" :key="(worklog.id)"
                        class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-900">

                        <td class="px-6 py-2 dark:text-gray-300">{{ index + 1 }}</td>
                        <td v-if="can('score.create')" class="px-6 py-2 dark:text-gray-300">
                            {{ worklog.user.name }}
                        </td>
                        <td class="px-6 py-2 dark:text-gray-300">{{ worklog.worklog_name }}</td>
                        <td class="px-6 py-2 dark:text-gray-300">{{ worklog.discription }}</td>
                        <td class="px-6 py-2 dark:text-gray-300">
                            <a :href="`/storage/${worklog.worklog_upload}`" target="_blank" class="text-blue-500">
                                View
                            </a>
                        </td>

                        <td class="px-6 py-2 text-center">
                            <span v-if="worklog.score">
                                {{ worklog.score.weekly_worklog_score }}/100
                            </span>
                            <span v-else class="text-gray-400">
                                __/100
                            </span>
                        </td>

                        <td class="px-6 py-3">
                            <div class="flex items-center justify-center gap-2">

                                <Link 
                                v-if="can('score.create')"
                                :href="route('weekly-worklogs.evaluate', worklog.id)" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold
                           text-white bg-green-600 rounded-md hover:bg-green-700 transition">
                                    <ClipboardCheck class="w-4 h-4" />

                                </Link>

                                <Link :href="route('weekly-worklogs.show', worklog.id)"
                                    class="p-2 rounded hover:bg-gray-200  bg-gray-800 dark:hover:bg-gray-700  "
                                    title="View">
                                    <FileText class="w-4 h-4 text-white" />
                                </Link>

                                <Link :href="route('weekly-worklogs.edit', worklog.id)"
                                    class="p-2 rounded bg-blue-500 hover:bg-blue-900" title="Edit">
                                    <Pencil class="w-4 h-4 text-white" />
                                </Link>

                                <button @click="deleteWeeklyWorklog(worklog.id)"
                                    class="p-2 rounded bg-red-500 hover:bg-red-900" title="Delete">
                                    <Trash2 class="w-4 h-4 text-white" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>
