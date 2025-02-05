<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <AuthenticatedLayout v-if=$page.props.auth.user>

    </AuthenticatedLayout>

    <GuestLayout  v-if=!$page.props.auth.user>

        <Head title="Página principal" />
    </GuestLayout>

</template>
