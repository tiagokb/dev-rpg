<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import Button from '@/Components/Button.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>

        <Head title="Criar sua conta" />

        <div class="flex justify-center">
            <div class="container flex gap-2 min-h-[750px]">

                <div class="flex justify-between items-center bg-mageBG bg-cover bg-center rounded-lg w-4/6">
                </div>

                <div class="flex justify-between items-center bg-charcoal-d12 rounded-lg w-2/6">

                    <form @submit.prevent="submit" class="w-full p-16 flex flex-col gap-2">

                        <h1 class="font-rpgSans text-white text-2xl">Criar uma conta</h1>

                        <div class="mt-4">
                            <label for="name" class="font-rpgSans text-sand-d8 text-xs font-thin">Seu nome</label>
                            <input
                                class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent"
                                id="name" type="text" v-model="form.name" required autofocus>
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div class="mt-4">
                            <label for="email" class="font-rpgSans text-sand-d8 text-xs font-thin">Seu e-mail</label>
                            <input
                                class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent"
                                id="email" type="email" v-model="form.email" required>
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div class="mt-4">
                            <label for="password" class="font-rpgSans text-sand-d8 text-xs font-thin">Sua senha</label>
                            <input
                                class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent"
                                id="password" type="password" v-model="form.password" required>
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <div class="mt-4">
                            <label for="password_confirmation"
                                class="font-rpgSans text-sand-d8 text-xs font-thin">Repita a senha</label>
                            <input
                                class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent"
                                id="password_confirmation" type="password" v-model="form.password_confirmation"
                                required>
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>


                        <div class="mt-5 gap-5 flex flex-col items-center">

                            <Button type="submit" formato="primary" class="w-full" :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing">
                                Cadastrar
                            </Button>

                            <Button formato="ghost" size="xs" class="w-full">
                                <Link :href="route('login')">Já tem uma conta?</Link>
                            </Button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
