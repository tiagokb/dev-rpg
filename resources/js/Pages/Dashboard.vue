<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from '@/Components/Button.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    campaigns: Object,
});

const form = useForm({});

const createCampaign = () => {
    form.get(route('campaigns.create'));
};

</script>

<template>

    <Head title="Dashboard" />
    <AuthenticatedLayout>

        <Head title="Painel" />
        <div class="flex justify-center">
            <div class="container flex flex-col gap-2 min-h-[350px] ">
                <div class="flex justify-between items-center bg-rogueelfBG bg-cover bg-center rounded-lg h-40 w-full">
                </div>
                <div class="flex flex-col p-8 bg-charcoal-d12 rounded-lg gap-5">
                    <div class="flex justify-end">
                        <h1 class="font-rpgSans text-sand-d8 text-2xl w-full">Campanhas</h1>
                        <Button size="xs" class="w-1/4" @click="createCampaign">Criar nova campanha</Button>
                    </div>
                    <div>
                    </div>
                    <div class="grid grid-cols-4 gap-4">
                        <div v-for="campaign in campaigns" :key="campaign.id" class="bg-charcoal-d12 outline outline-1 outline-charcoal-d10 rounded-lg col-1">
                            <div class="h-48 bg-cover bg-center rounded-lg"
                                :style="campaign.image_url ? { backgroundImage: `url(${campaign.image_url})` } : { backgroundImage: `url(/images/cover.jpg)` }">
                            </div>
                            <div class="p-6 text-sand-d6">
                                <h2 class="font-rpgSans text-sand-d8">{{ campaign.name }}</h2>
                                <p class="text-sand-d6 text-sm"> {{ campaign.description }}
                                </p>
                                <div class="mt-4 flex gap-2">
                                    <Button formato="ghost" size="xs" class="w-full">Editar</Button>
                                    <Button formato="primary" size="xs" class="w-full">Iniciar</Button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
