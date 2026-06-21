<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import { type BreadcrumbItem } from '@/types'

const props = defineProps<{
    worklog: {
        id: number
        score?: {
            weekly_worklog_score: number | null
        }
    }
}>()

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Evaluate Score',
        href: route('weekly-worklogs.evaluate', props.worklog.id),
    },
]

// Inertia form
const form = useForm({
    weekly_worklog_score: props.worklog.score?.weekly_worklog_score ?? '',
})

const submit = () => {
    form.post(route('weekly-worklogs.evaluate-score', props.worklog.id))
}
</script>

<template>
    <Head title="Evaluate Score" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <form @submit.prevent="submit" class="space-y-4">

            <input
                type="number"
                min="0"
                max="100"
                v-model="form.weekly_worklog_score"
                class="border rounded px-3 py-2 w-64"
                placeholder="Enter score"
                required
            />

            <button
                type="submit"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                :disabled="form.processing"
            >
                Evaluate
            </button>

            <p v-if="form.errors.weekly_worklog_score" class="text-red-500 text-sm">
                {{ form.errors.weekly_worklog_score }}
            </p>

        </form>
    </AppLayout>
</template>
