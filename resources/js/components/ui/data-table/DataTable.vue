<script setup lang="ts">
import { computed } from 'vue'
import { cn } from '@/lib/utils'

interface Pagination {
    current_page: number
    last_page: number
    per_page: number
    total: number
}

interface Props {
    data: any[]
    columns: string[]
    pagination: Pagination
}

const props = defineProps<Props>()

const emit = defineEmits<{
    (e: 'page-change', page: number): void
    (e: 'per-page-change', perPage: number): void
}>()

function changePage(page: number) {
    if (page >= 1 && page <= props.pagination.last_page) {
        emit('page-change', page)
    }
}

function handlePerPageChange(event: Event) {
    const target = event.target as HTMLSelectElement
    const perPage = parseInt(target.value)
    emit('per-page-change', perPage)
}

const visiblePages = computed(() => {
    const current = props.pagination.current_page
    const last = props.pagination.last_page
    const pages: number[] = []
    
    const start = Math.max(1, current - 2)
    const end = Math.min(last, current + 2)
    
    for (let i = start; i <= end; i++) {
        pages.push(i)
    }
    
    return pages
})
</script>

<template>
    <div class="flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th 
                            v-for="column in columns" 
                            :key="column"
                            class="px-6 py-3"
                        >
                            <slot :name="`header-${column}`" :column="column">
                                {{ column }}
                            </slot>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr 
                        v-for="(item, index) in data" 
                        :key="item.id"
                        class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900 dark:even:bg-gray-800"
                    >
                        <td 
                            v-for="column in columns" 
                            :key="column"
                            class="px-6 py-2"
                        >
                            <slot :name="column" :item="item" :index="index">
                                {{ item[column] }}
                            </slot>
                        </td>
                    </tr>
                    <tr v-if="data.length === 0">
                        <td :colspan="columns.length" class="text-center py-4 text-gray-400">
                            <slot name="empty">No data found</slot>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="pagination.total > 0" class="flex items-center justify-between mt-4">
            <div class="flex items-center gap-2">
                <label class="text-xs text-gray-600 dark:text-gray-400">ចំនួនក្នុងមួយទំព័រ:</label>
                <select 
                    :value="pagination.per_page"
                    @change="handlePerPageChange"
                    class="appearance-none pl-2 pr-6 py-1 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-xs"
                >
                    <option :value="10">១០</option>
                    <option :value="20">២០</option>
                    <option :value="30">៣០</option>
                    <option :value="40">៤០</option>
                    <option :value="50">៥០</option>
                    <option :value="100">១០០</option>
                </select>
            </div>

            <div v-if="pagination.last_page > 1" class="flex items-center gap-1">
                <button 
                    @click="changePage(1)"
                    :disabled="pagination.current_page === 1"
                    :class="cn(
                        'px-2 py-1 rounded text-xs',
                        pagination.current_page === 1 
                            ? 'text-gray-400 cursor-not-allowed' 
                            : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800'
                    )"
                >
                    ដំបូភ្ជាន់
                </button>

                <button 
                    @click="changePage(pagination.current_page - 1)"
                    :disabled="pagination.current_page === 1"
                    :class="cn(
                        'px-2 py-1 rounded text-xs',
                        pagination.current_page === 1 
                            ? 'text-gray-400 cursor-not-allowed' 
                            : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800'
                    )"
                >
                    មុន
                </button>

                <button 
                    v-for="page in visiblePages"
                    :key="page"
                    @click="changePage(page)"
                    :class="cn(
                        'px-3 py-1 rounded text-xs font-medium',
                        page === pagination.current_page
                            ? 'bg-blue-600 text-white'
                            : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800'
                    )"
                >
                    {{ page }}
                </button>

                <button 
                    @click="changePage(pagination.current_page + 1)"
                    :disabled="pagination.current_page === pagination.last_page"
                    :class="cn(
                        'px-2 py-1 rounded text-xs',
                        pagination.current_page === pagination.last_page 
                            ? 'text-gray-400 cursor-not-allowed' 
                            : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800'
                    )"
                >
                    បន្ទាប់
                </button>

                <button 
                    @click="changePage(pagination.last_page)"
                    :disabled="pagination.current_page === pagination.last_page"
                    :class="cn(
                        'px-2 py-1 rounded text-xs',
                        pagination.current_page === pagination.last_page 
                            ? 'text-gray-400 cursor-not-allowed' 
                            : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800'
                    )"
                >
                    ចុងក្រោយ
                </button>
            </div>

            <div class="text-xs text-gray-500 dark:text-gray-400">
                {{ pagination.current_page }} / {{ pagination.last_page }} ({{ pagination.total }} សរុប)
            </div>
        </div>
    </div>
</template>