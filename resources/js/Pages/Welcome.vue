<script setup>
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
    <GuestLayout>
        <Head title="Página principal" />
        <div class="flex justify-center">
            <div class="container flex flex-col gap-2 min-h-[350px] ">
                <div class="flex justify-between items-center bg-rogueBG bg-cover bg-center rounded-lg h-80 w-full">
                </div>
                <div class="flex justify-between items-center bg-charcoal-d12 rounded-lg w-full h-80">
            </div>

            </div>
        </div>
    </GuestLayout>

</template>
