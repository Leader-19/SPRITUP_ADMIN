<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import { Plus } from 'lucide-vue-next'
import DataTable from '@/components/ui/data-table/DataTable.vue'
import { can } from '@/lib/can'
import { type BreadcrumbItem } from '@/types'

interface Category {
    id: number
    title: string
    description: string | null
    documents_count: number
}

interface Pagination {
    current_page: number
    last_page: number
    per_page: number
    total: number
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'ប្រភេទ',
        href: '/categories',
    },
]

const props = defineProps<{
    categories: Category[]
    pagination: Pagination
}>()

function deleteCategory(id: number) {
    if (confirm("តើអ្នកនិងលុបប្រភេទ")) {
        router.delete(route('categories.destroy', id));
    }
}

function handlePageChange(page: number) {
    router.get(route('categories.index'), {
        per_page: props.pagination.per_page,
        page: page
    }, { preserveState: true })
}

function handlePerPageChange(perPage: number) {
    router.get(route('categories.index'), {
        per_page: perPage,
        page: 1
    }, { preserveState: true })
}
</script>

<template>
    <Head title="ប្រភេទ" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-3">

            <Link 
            v-if="can('category.create')"
            :href="route('categories.create')" 
            class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold
                   text-white bg-blue-600 rounded-md hover:bg-blue-700 transition">
                <Plus class="w-4 h-4" />
                បង្កើត​ ប្រភេទថ្មី
            </Link>

            <DataTable 
                :data="props.categories"
                :pagination="props.pagination"
                :columns="['stt', 'title', 'description', 'documents_count', 'actions']"
                class="mt-3"
                @page-change="handlePageChange"
                @per-page-change="handlePerPageChange"
            >
                <template #header-stt>លរ</template>
                <template #header-title>ឈ្មោះ</template>
                <template #header-description>ការរៀបរាប់</template>
                <template #header-documents_count>ឯកសារ</template>
                <template #header-actions>សកម្មភាព</template>

                <template #stt="{ index }">
                    {{ (props.pagination.current_page - 1) * props.pagination.per_page + index + 1 }}
                </template>

                <template #title="{ item }">
                    {{ item.title }}
                </template>

                <template #description="{ item }">
                    {{ item.description }}
                </template>

                <template #documents_count="{ item }">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ item.documents_count }} ឯកសារ
                    </span>
                </template>

                <template #actions="{ item }">
                    <div class="flex gap-2">
                        <Link :href="route('categories.show', item.id)"
                            class="cursor-pointer px-3 py-2 text-xs mr-2 font-medium text-white bg-gray-700 rounded">
                            បង្ហាញ
                        </Link>

                        <Link :href="route('categories.edit', item.id)"
                            class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-500 rounded">
                            កែរ
                        </Link>
                        <button @click="deleteCategory(item.id)"
                            class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-red-500 rounded ml-2">
                            លុប
                        </button>
                    </div>
                </template>
            </DataTable>
        </div>
    </AppLayout>
</template>