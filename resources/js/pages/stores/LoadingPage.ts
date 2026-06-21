import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useLoadingStore = defineStore('loading', () => {
    const isLoading = ref(false)

    const start = () => {
        isLoading.value = true
    }

    const stop = () => {
        isLoading.value = false
    }

    return { isLoading, start, stop }
})