<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from '@/Components/Button.vue';
import Modal from '@/Components/Modal.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';


defineProps({
    campaigns: Object,
});

const NewCampaignModal = ref(false);

const CampaignModal = () => {
    NewCampaignModal.value = true;
};

const CloseCampaignModal = () => {
    NewCampaignModal.value = false;

    form.clearErrors();
    form.reset();
};

const form = useForm({
    name: '',
    description: '',
    image_url: '',
});

const submit = () => {
    form.post(route('campaigns.store'), {
        onFinish: () => {
            // Fecha o modal apenas depois que a requisição terminar
            CloseCampaignModal();
        }
    });
    
};

</script>

<template>

<Head title="Campanhas" />
    <AuthenticatedLayout>
        <div class="flex justify-center">
            <div class="container flex flex-col gap-2 min-h-[350px] ">
                <div class="flex justify-between items-center bg-rogueelfBG bg-cover bg-center rounded-lg h-80 w-full">
                </div>
                <div class="flex flex-col p-8 bg-charcoal-d12 rounded-lg gap-5">
                    <div class="grid grid-cols-2">
                        <div class="col-span-1">
                            <h1 class="font-rpgSans text-sand-d6 text-2xl w-full">Campanhas</h1>
                        </div>
                        <div class="col-span-1 flex justify-end">
                            <Button size="xs" @click="CampaignModal">Criar nova campanha</Button>
                        </div>
                    </div>
                    <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3">
                        <div v-for="campaign in campaigns" :key="campaign.id"
                            class="bg-charcoal-d12 outline outline-1 outline-charcoal-d10 rounded-lg col-1">
                            <div class="h-48 bg-cover bg-center rounded-lg"
                                :style="campaign.image_url ? { backgroundImage: `url(${campaign.image_url})` } : { backgroundImage: `url(/images/cover.jpg)` }">
                            </div>
                            <div class="p-6 text-sand-d6">
                                <h2 class="font-rpgSans text-sand-d8">{{ campaign.name }} - {{ campaign.id }}</h2>
                                <p class="text-sand-d6 text-sm line-clamp-3"> {{ campaign.description }}
                                </p>
                                <div class="mt-4 flex gap-2">
                                    <Link class="w-full" :href="route('campaigns.edit', campaign.id)">
                                    <Button formato="ghost" size="xs" class="w-full">Editar</Button></Link>

                                    <Button formato="primary" size="xs" class="w-full">Iniciar</Button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="NewCampaignModal" @close="CloseCampaignModal">
            <div class="p-6">
                <form @submit.prevent="submit" class="flex flex-col gap-4">
                    <header>
                        <h1 class="font-rpgSans text-white text-2xl">Criar nova campanha</h1>
                        <p class="mt-1 text-sm text-sand-d6">
                            Certifique-se de que sua conta esteja usando uma senha longa e aleatória para permanecer
                            seguro.
                        </p>
                    </header>

                    <div>
                        <label class="font-rpgSans text-sand-d8 text-xs font-thin">Nome da Campanha</label>
                        <input v-model="form.name" type="text"
                            class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent"
                            required>
                    </div>
                    <div>
                        <label class="font-rpgSans text-sand-d8 text-xs font-thin">Descrição</label>
                        <input v-model="form.description"
                            class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent"></input>
                    </div>
                    <div>
                        <label class="font-rpgSans text-sand-d8 text-xs font-thin">Imagem URL</label>
                        <input v-model="form.image_url"
                            class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent">
                    </div>
                    <Button formato="primary" class="mt-4 w-full" type="submit">Criar Campanha</Button>
                    <Button formato="secondary" size="xs" @click="CloseCampaignModal">Cancelar</Button>
                </form>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
