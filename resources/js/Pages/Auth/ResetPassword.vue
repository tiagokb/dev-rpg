<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Button from '@/Components/Button.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>

        <Head title="Reset Password" />

        <div class="flex justify-center">
            <div class="container flex gap-2 min-h-[750px]">

                <div class="flex justify-between items-center bg-mageBG bg-cover bg-center rounded-lg w-4/6">
                </div>

                <div class="flex justify-between items-center bg-charcoal-d12 rounded-lg w-2/6">


                    <form @submit.prevent="submit"  class="w-full p-16 flex flex-col gap-2">
                        <div>
                            <InputLabel for="email" value="Email" />

                            <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                                autofocus autocomplete="username" />

                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="password" value="Password" />

                            <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password"
                                required autocomplete="new-password" />

                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="password_confirmation" value="Confirm Password" />

                            <TextInput id="password_confirmation" type="password" class="mt-1 block w-full"
                                v-model="form.password_confirmation" required autocomplete="new-password" />

                            <InputError class="mt-2" :message="form.errors.password_confirmation" />
                        </div>

                        <div class="mt-4 flex items-center justify-end">
                            <Button type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Reset Password
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
