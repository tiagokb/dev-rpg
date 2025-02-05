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
                    <Button size="xs">Página Inicial</Button>
                    </Link>

                    <Link href="/dashboard">
                    <Button size="xs">Painel</Button>
                    </Link>
                </div>
                <Link href="/">
                <ApplicationLogo class="h-14 w-14  text-gray-500" />
                </Link>
                <div class="flex gap-2">
                    <Link :href="route('profile.edit')">
                    <Button size="xs">{{ $page.props.auth.user.name }}</Button>
                    </Link>

                    <Link :href="route('logout')" method="post" as="button">
                    <Button size="xs">Sair</Button>
                    </Link>

                </div>
            </div>
        </div>
        <slot />
    </div>
</template>