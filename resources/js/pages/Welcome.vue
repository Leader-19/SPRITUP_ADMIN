<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
import Login from './auth/Login.vue';


withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>

    <Login :canResetPassword="true" :canRegister="true" />

    <div class="hidden h-14.5 lg:block"></div>

</template>
