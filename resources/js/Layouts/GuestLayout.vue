<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Button from '@/Components/Button.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-charcoal-d20 pt-2 sm:justify-center sm:pt-0">

        <div class="flex justify-center bg-charcoal-d20">
            <div class="container py-8 flex justify-between items-center">
                <div class="flex gap-2">
                    <Link href="/">
                    <ApplicationLogo class="h-14 w-14  text-gray-500" />
                    </Link>
                </div>

                <div class="flex gap-2">
                    <Link href="/">
                    <Button size="xs">Página Inicial</Button>
                    </Link>

                    <Link href="/register">
                    <Button size="xs">Criar sua conta</Button>
                    </Link>

                    <Link href="/login">
                    <Button size="xs">Entrar</Button>
                    </Link>
                </div>
            </div>
        </div>

        <slot></slot>
    </div>
</template>
