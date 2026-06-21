<script setup lang="ts">
import { useLoadingStore } from '@/pages/stores/LoadingPage';

const loading = useLoadingStore()
</script>

<template>
    <div
        v-if="loading.isLoading"
        class="fixed inset-0 bg-black/30 flex items-center justify-center z-50"
    >
        <div class="flex flex-col items-center gap-3">
            <!-- Spinner -->
            <div class="w-10 h-10 border-4 border-gray-300 border-t-blue-600 rounded-full animate-spin"></div>

            <!-- Text -->
            <p class="text-sm text-white">Loading...</p>
        </div>
    </div>
</template>