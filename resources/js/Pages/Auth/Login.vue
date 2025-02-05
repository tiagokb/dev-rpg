<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import Button from '@/Components/Button.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <div class="min-h-screen bg-charcoal-d20 pt-2 sm:justify-center sm:pt-0">
        <GuestLayout>
            <Head title="Entrar na conta" />

            <div class="flex justify-center">
                <div class="container flex gap-2 min-h-[750px]">
                    <div class="flex justify-between items-center bg-barbarianBG bg-cover bg-center rounded-lg w-4/6">
                    </div>
                    <div class="flex justify-between items-center bg-charcoal-d12 rounded-lg w-2/6">

                        <form @submit.prevent="submit" class="w-full p-16 flex flex-col gap-2">

                            <h1 class="font-rpgSans text-white text-2xl">Entre agora</h1>

                            <div class="mt-4">
                                <label for="email" class="font-rpgSans text-sand-d8 text-xs font-thin">Seu e-mail</label>
                                <input
                                    class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent"
                                    id="email" type="email" v-model="form.email" required autofocus>
                            </div>

                            <div>
                                <label for="password" class="font-rpgSans text-sand-d8 text-xs font-thin">Sua senha</label>
                                <input
                                    class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent"
                                    id="password" type="password" v-model="form.password">
                            </div>

                            <div class="mt-4 gap-5 flex flex-col">
                                <InputError class="mt-2" :message="form.errors.email" />

                                <Button type="submit" formato="primary"
                                class="w-full"
                                    :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Entrar
                                </Button>

                                <Link v-if="canResetPassword" :href="route('password.request')">
                                <Button formato="ghost" size="xs" class="w-full"
                                    :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Lembrar minha senha
                                </Button>
                                </Link>

                                <Link href="/register">
                                <Button formato="ghost" size="xs" class="w-full"
                                    :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Não tenho uma conta
                                </Button>
                                </Link>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </GuestLayout>

    </div>
</template>
