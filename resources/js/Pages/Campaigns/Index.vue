<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CampaignCard from '@/Components/ui/CardCampaign.vue';
import Button from '@/Components/Button.vue';
import Modal from '@/Components/Modal.vue';

import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    campaigns: Object
});

const form = useForm({
    name: '',
    description: '',
    image_url: '',
});

const submit = () => {
    form.post(route('campaigns.store'), {
        onSuccess: () => {
            closeModal('campaign')
            router.push(route('campaigns.index'));
        }
    });
};

const inviteForm = useForm({
    code: ''
});

const submitInvite = () => {
    inviteForm.post(route('campaigns.join'), {
        onSuccess: () => {
            closeModal('invite')
            router.push(route('campaigns.index'));
        }
    });
};

const modals = ref({
    invite: false,
    campaign: false
});

const openModal = (type) => (modals.value[type] = true);

const closeModal = (type) => {
    modals.value[type] = false;
    if (type === 'campaign') form.reset();
    if (type === 'invite') inviteForm.reset();
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
                        <div class="col-span-1 flex justify-end gap-2">
                            <Button size="xs" @click="openModal('campaign')">Criar nova campanha</Button>
                            <Button size="xs" @click="openModal('invite')">Participar de uma campanha</Button>
                        </div>
                    </div>
                    <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3">
                        <CampaignCard v-for="campaign in campaigns.data" :key="campaign.id" :campaignsData="campaign" />
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="modals.campaign" @close="closeModal('campaign')">
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
                    <Button formato="secondary" size="xs" @click="closeModal('campaign')">Cancelar</Button>
                </form>
            </div>
        </Modal>

        <Modal :show="modals.invite" @close="closeModal('invite')">
            <div class="p-6">
                <form @submit.prevent="submitInvite" class="flex flex-col gap-4">
                    <header>
                        <h1 class="font-rpgSans text-white text-2xl">Participar de uma campanha</h1>
                        <p class="mt-1 text-sm text-sand-d6">
                            Insira o código de 6 caracteres fornecido pelo Mestre da Campanha
                        </p>
                    </header>

                    <div>
                        <label class="font-rpgSans text-sand-d8 text-xs font-thin">Código da Campanha</label>
                        <input v-model="inviteForm.code" type="text" maxlength="10"
                            class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent"
                            required @input="inviteForm.code = inviteForm.code.toUpperCase().replace(/[^A-Z0-9]/g, '')">
                        <p v-if="inviteForm.errors.code" class="text-red-400 text-xs mt-1">
                            {{ inviteForm.errors.code }}
                        </p>
                    </div>

                    <Button formato="primary" class="mt-4 w-full" type="submit" :disabled="inviteForm.processing">
                        Participar
                    </Button>
                    <Button formato="secondary" size="xs" @click="closeModal('invite')">Cancelar</Button>
                </form>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
