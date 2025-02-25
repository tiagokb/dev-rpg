<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Button from '@/Components/Button.vue';
import { Link } from '@inertiajs/vue3';
import {ref } from 'vue';

const viewToast = ref(true);

</script>

<template>
    <div class="min-h-screen bg-charcoal-d20 pt-2 sm:justify-center sm:pt-0">
        <div class="flex justify-center bg-charcoal-d20">
            <div class="container py-8 md:flex-row flex-col flex gap-3 justify-between items-center">

                <div class="flex gap-2">
                    <Link :href="route('campaigns.index')">
                    <ApplicationLogo class="h-14 w-14  text-gray-500" />
                    </Link>
                </div>
                <div class="flex gap-2">
                    <Link :href="route('campaigns.index')">
                    <Button size="xs">Campanhas</Button>
                    </Link>
                    <Link :href="route('profile.edit')">
                    <Button size="xs">{{ $page.props.auth.user.name }}
                    </Button>
                    </Link>

                    <Link :href="route('logout')" method="post" as="button">
                    <Button size="xs"> Sair                    </Button>
                    </Link>

                </div>
            </div>
        </div>
        <div v-show="viewToast" v-if="$page.props.flash.success" id="toast-success"
            class="fixed animate-bounce flex items-center w-full max-w-xs p-4 space-x-4 text-gray-500 bg-white rounded-lg shadow-sm top-40 right-8">
            <div
                class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                </svg>
                <span class="sr-only">Check icon</span>
            </div>
            <div class="ms-3 text-sm font-normal">{{ $page.props.flash.success }}</div>
            <button @click="viewToast = false" type="button"
                class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                data-dismiss-target="#toast-success" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>

        <div id="toast-danger" v-show="viewToast" v-if="$page.props.flash.error"
            class="fixed fade-out flex items-center w-full max-w-xs p-4 space-x-4 text-gray-500 bg-white rounded-lg shadow-sm top-40 right-8">
            <div
                class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z" />
                </svg>
                <span class="sr-only">Error icon</span>
            </div>
            <div class="ms-3 text-sm font-normal">{{ $page.props.flash.error }}</div>
            <button @click="viewToast = false" type="button"
                class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                data-dismiss-target="#toast-danger" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>
        <slot />
    </div>
</template>