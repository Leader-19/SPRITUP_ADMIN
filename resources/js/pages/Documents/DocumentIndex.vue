<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import {
    FileText,
    Plus,
    Pencil,
    Trash2,
    Search
} from 'lucide-vue-next'
import { type BreadcrumbItem } from '@/types'
import { ref } from 'vue'
import { can } from '@/lib/can'
import DataTable from '@/components/ui/data-table/DataTable.vue'

interface Document {
    id: number
    doc_name: string
    doc_title: string
    description: string | null
    doc_upload: string
    category_id: number
}

interface Category {
    id: number
    title: string
}

interface Pagination {
    current_page: number
    last_page: number
    per_page: number
    total: number
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'ឯកសារ',
        href: '/documents',
    },
]

const props = defineProps<{
    documents: Document[],
    categories: Category[],
    pagination: Pagination
}>()

const selectedCategory = ref<number | null>(null)
const searchQuery = ref<string>('')

function deleteDocument(id: number) {
    if (confirm("Are you want to delete this Final Slide")) {
        router.delete(route('documents.destroy', id));
    }
}

function changeItemsPerPage(perPage: number) {
    router.get(route('documents.index'), {
        per_page: perPage,
        category_id: selectedCategory.value,
        search: searchQuery.value,
        page: 1
    }, { preserveState: true })
}

function changePage(page: number) {
    router.get(route('documents.index'), {
        per_page: props.pagination.per_page,
        category_id: selectedCategory.value,
        search: searchQuery.value,
        page: page
    }, { preserveState: true })
}

function filterByCategory(categoryId: number | null) {
    selectedCategory.value = categoryId
    router.get(route('documents.index'), {
        category_id: categoryId,
        search: searchQuery.value,
        per_page: props.pagination.per_page,
        page: 1
    }, { preserveState: true })
}

function handleSearch() {
    router.get(route('documents.index'), {
        search: searchQuery.value,
        category_id: selectedCategory.value,
        per_page: props.pagination.per_page,
        page: 1
    }, { preserveState: true })
}
</script>

<template>
    <Head title="ឯកសារ" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-3">

            <!-- Create Button and Search -->
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center gap-4">
                    <Link
                        v-if="can('document.create')"
                        :href="route('documents.create')"
                        class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700 transition"
                    >
                        <Plus class="w-4 h-4" />
                        បង្កើត​ ឯកសារ
                    </Link>

                    <!-- Search Input -->
                    <div class="relative flex items-center">
                        <input
                            v-model="searchQuery"
                            @keyup.enter="handleSearch"
                            type="text"
                            placeholder="ស្វែងរក..."
                            class="w-64 pl-9 pr-3 py-1.5 rounded-l border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                        <Search class="absolute left-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                        <button
                            @click="handleSearch"
                            class="px-3 py-1.5 rounded-r border border-l-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-xs hover:bg-gray-100 dark:hover:bg-gray-600"
                        >
                            ស្វែងរក
                        </button>
                    </div>
                </div>
            </div>

            <!-- Category Row -->
            <div class="flex flex-wrap gap-2 mt-4 mb-3">

                <!-- All -->
                <button
                    @click="filterByCategory(null)"
                    :class="[
                        'px-4 py-1 text-xs font-medium rounded transition',
                        selectedCategory === null
                            ? 'bg-blue-600 text-white'
                            : 'bg-gray-200 text-gray-700 hover:bg-blue-500 hover:text-white'
                    ]"
                >
                    All
                </button>

                <!-- Categories -->
                <button
                    v-for="cat in props.categories"
                    :key="cat.id"
                    @click="filterByCategory(cat.id)"
                    :class="[
                        'px-4 py-1 text-xs font-medium rounded transition',
                        selectedCategory === cat.id
                            ? 'bg-blue-600 text-white'
                            : 'bg-gray-200 text-gray-700 hover:bg-blue-500 hover:text-white'
                    ]"
                >
                    {{ cat.title }}
                </button>

            </div>

            <DataTable 
                :data="props.documents"
                :pagination="props.pagination"
                :columns="['stt', 'doc_name', 'doc_title', 'doc_upload', 'description', 'actions']"
                class="mt-3"
                @page-change="changePage"
                @per-page-change="changeItemsPerPage"
            >
                <template #header-stt>លរ</template>
                <template #header-doc_name>ឈ្មោះ​ ឯកសារ</template>
                <template #header-doc_title>ចំណងជើង</template>
                <template #header-doc_upload>File</template>
                <template #header-description>រៀបរាប់</template>
                <template #header-actions>សកម្មភាព</template>

                <template #stt="{ index }">
                    {{ (props.pagination.current_page - 1) * props.pagination.per_page + index + 1 }}
                </template>

                <template #doc_upload="{ item }">
                    <a
                        :href="`/storage/${item.doc_upload}`"
                        target="_blank"
                        class="text-blue-600 hover:underline"
                    >
                        View
                    </a>
                </template>

                <template #actions="{ item }">
                    <div class="flex justify-center gap-2">

                        <Link
                            :href="route('documents.show', item.id)"
                            class="p-2 bg-gray-800 rounded hover:bg-gray-700"
                        >
                            <FileText class="w-4 h-4 text-white" />
                        </Link>

                        <Link
                            :href="route('documents.edit', item.id)"
                            class="p-2 bg-blue-500 rounded hover:bg-blue-900"
                        >
                            <Pencil class="w-4 h-4 text-white" />
                        </Link>

                        <button
                            @click="deleteDocument(item.id)"
                            class="p-2 bg-red-500 rounded hover:bg-red-700"
                        >
                            <Trash2 class="w-4 h-4 text-white" />
                        </button>

                    </div>
                </template>
            </DataTable>

        </div>
    </AppLayout>
</template>