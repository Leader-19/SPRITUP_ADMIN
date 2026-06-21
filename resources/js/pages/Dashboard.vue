<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import Chart from 'chart.js/auto';

interface Category {
    id: number
    title: string
    description: string | null
    documents_count: number
}

withDefaults(defineProps<{
    stats?: {
        interns: number;
        companies: number;
        applications: number;
        accepted: number;
    };
    categories: Category[]
}>(), {
    stats: () => ({
        interns: 0,
        companies: 0,
        applications: 0,
        accepted: 0,
    }),
    categories: () => []
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const barChartRef = ref<HTMLCanvasElement | null>(null);
const lineChartRef = ref<HTMLCanvasElement | null>(null);
const pieChartRef = ref<HTMLCanvasElement | null>(null);

onMounted(() => {
    // Bar Chart
    if (barChartRef.value) {
        new Chart(barChartRef.value, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                datasets: [
                    {
                        label: 'Applications',
                        data: [10, 15, 8, 12, 20],
                        backgroundColor: '#3b82f6',
                    },
                    {
                        label: 'Accepted',
                        data: [5, 8, 4, 7, 10],
                        backgroundColor: '#10b981',
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Applications vs Accepted (Bar Chart)' },
                },
            },
        });
    }

    // Line Chart
    if (lineChartRef.value) {
        new Chart(lineChartRef.value, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                datasets: [
                    {
                        label: 'Applications',
                        data: [10, 15, 8, 12, 20],
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59,130,246,0.2)',
                        fill: true,
                        tension: 0.4,
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Applications Trend (Line Chart)' },
                },
            },
        });
    }

    // Pie Chart
    if (pieChartRef.value) {
        new Chart(pieChartRef.value, {
            type: 'pie',
            data: {
                labels: ['Pending', 'Accepted', 'Rejected'],
                datasets: [
                    {
                        label: 'Application Status',
                        data: [12, 7, 3],
                        backgroundColor: ['#facc15', '#10b981', '#ef4444'],
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Application Status Distribution (Pie Chart)' },
                },
            },
        });
    }
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4">
            <!-- Categories with Document Counts -->
            <div class="rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4">
                <h3 class="text-lg font-semibold mb-4">ប្រភេទនៃឯកសារ</h3>
                <div class="grid gap-4 md:grid-cols-4 sm:grid-cols-2">
                    <div
                        v-for="category in categories"
                        :key="category.id"
                        class="p-4 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ category.title }}</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1" v-if="category.description">
                                    {{ category.description }}
                                </p>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ category.documents_count }}
                            </span>
                        </div>
                    </div>

                    <div v-if="categories.length === 0" class="col-span-full text-center text-gray-400 py-4">
                        មិនមានប្រភេទឯកសារ
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>
