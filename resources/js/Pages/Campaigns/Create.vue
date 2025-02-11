<script setup>
import { useForm } from '@inertiajs/vue3';
import Button from '@/Components/Button.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    campaigns: Object,
});


const form = useForm({
    name: '',
    description: '',
    image_url: '',
});

const submit = () => {
    form.post(route('campaigns.store'));
};

</script>

<template>
    <AuthenticatedLayout>

        {{ campaigns }}
        <div class="container mx-auto p-8">
            <h1 class="text-2xl font-bold text-white">Criar Nova Campanha</h1>

            <form @submit.prevent="submit" class="mt-4">
                <label class="block text-white">Nome da Campanha</label>
                <input v-model="form.name" type="text" class="w-full bg-gray-800 text-white p-2 rounded mt-1" required>

                <label class="block text-white mt-4">Descrição</label>
                <textarea v-model="form.description" class="w-full bg-gray-800 text-white p-2 rounded mt-1"></textarea>

                <label class="block text-white mt-4">Imagem URL</label>
                <input v-model="form.image_url" class="w-full bg-gray-800 text-white p-2 rounded mt-1">

                <Button type="submit" formato="primary" class="mt-4 w-full">Criar Campanha</Button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
