<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from '@/Components/Button.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import { FilePenLine, Save } from 'lucide-vue-next';

import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'
import pt from 'dayjs/locale/pt-br'
import utc from 'dayjs/plugin/utc'

dayjs.extend(relativeTime);
dayjs.extend(utc)


const props = defineProps({
    campaign: Object,
});

const isCopied = ref(false);
let timeoutId = null;
const isLeaving = ref(false);

const editedCampaign = ref({ ...props.campaign });
const editingInfos = ref(false);
const editingImage = ref(false);


const showFeedbackCode = () => {
    isCopied.value = true;
    if (timeoutId) clearTimeout(timeoutId);
    timeoutId = setTimeout(() => {
        isCopied.value = false;
    }, 2000);
};

const copyCode = async () => {
    try {
        await navigator.clipboard.writeText(props.campaign.invite_code);
        showFeedbackCode()
    } catch (err) {
        console.error('Falha ao copiar código:', err);
        // Fallback para navegadores antigos
        showFeedbackCode()

        const textarea = document.createElement('textarea');
        textarea.value = props.campaign.invite_code;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);

    }
};

const toggleEditInfos = () => {
    if (editingInfos.value) {
        // Salvar alterações no backend
        router.put(`/campaigns/${props.campaign.id}`, {
            name: editedCampaign.value.name,
            description: editedCampaign.value.description,
        });
    }
    editingInfos.value = !editingInfos.value;
};

const toggleEditImage = () => {
    if (editingImage.value) {
        useForm({ image_url: editedCampaign.value.image_url }).put(`/campaigns/${props.campaign.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                router.reload({ only: ['campaign'] }); // Recarrega apenas os dados da campanha
            },
        });
    }
    editingImage.value = !editingImage.value;
};

const leaveCampaign = async () => {
    if (!confirm("Tem certeza que deseja sair desta campanha?")) return

    isLeaving.value = true

    router.delete(route('campaigns.leave', props.campaign.id), {
        onFinish: () => {
            isLeaving.value = false
        }
    })
}

const deleteCampaign = (campaignId) => {
    if (confirm('Tem certeza que deseja excluir esta campanha? Esta ação é irreversível!')) {
        router.delete(route('campaigns.destroy', campaignId));
    }
};


</script>

<template>

    <Head title="Campanhas" />
    <AuthenticatedLayout>
        <div class="flex justify-center">
            <div class="container gap-2 min-h-[350px] grid grid-cols-4 mb-8">
                <div class="col-span-4 flex justify-between items-center bg-cover bg-center rounded-lg h-80 w-full"
                    :style="{
                        backgroundImage: editedCampaign.image_url ? `url(${editedCampaign.image_url})` : 'url(/images/cover.jpg)'
                    }">

                    <div class="p-4 flex flex-col gap-4 col-span-1 col-start-5">

                        <label v-if="editingImage" for="editImage"
                            class="font-rpgSans text-sand-d8 text-xs font-thin">Digite o URL da imagem</label>

                        <input v-if="editingImage"
                            class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent"
                            id="editImage" type="text" v-model="editedCampaign.image_url">

                        <Button v-if="campaign.is_master" @click="toggleEditImage" formato="secondary" size="xs"> {{
                            editingImage ? 'Salvar' :
                                'Trocar imagem' }} </Button>
                    </div>
                </div>
                <div
                    class="col-span-4 order-1 md:order-2 md:col-span-3 flex flex-col p-8 bg-charcoal-d12 rounded-lg gap-2">
                    <h1 class="font-rpgSans text-sand-d6 text-2xl flex gap-4 justify-between items-center">
                        <input v-if="editingInfos" v-model="editedCampaign.name"
                            class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent" />
                        <span v-else>{{ campaign.name }}</span>

                        <Button @click="toggleEditInfos" formato="secondary" size="icon">
                            <FilePenLine v-if="!editingInfos" class="w-4 h-4" />
                            <Save v-else class="w-4 h-4" />
                        </Button>
                    </h1>
                    <p class="text-sand-d6">
                        <textarea v-if="editingInfos" v-model="editedCampaign.description"
                            class="text-sand-d6 mt-1 block w-full h-40 border-solid border-0 border-b border-sand-d8 bg-transparent "></textarea>
                        <span v-else>{{ campaign.description }}</span>

                    </p>
                </div>
                <div class="flex flex-col gap-2 col-span-4 md:col-span-1">
                    <div class="flex flex-col p-8 bg-charcoal-d12 rounded-lg gap-4">
                        <h1 class="font-rpgSans text-sand-d6 text-2xl"> Ações</h1>
                        <Button formato="primary" class="w-full" size="xs">Iniciar</Button>
                        <p class="text-sand-d6 text-xs">Criado por {{ campaign.master.name }} em 
                            {{ campaign.created_at }} || {{ campaign.updated_at }}</p>
                    </div>
                    <div v-if="campaign.is_master" class="flex flex-col p-8 bg-charcoal-d12 rounded-lg gap-4">
                        <h1 class="font-rpgSans text-sand-d6 text-2xl flex">Convite</h1>
                        <p class="mt-1 text-sm text-sand-d6">
                            Copie o código e envie para o jogador. Eles poderão se juntar à sua campanha com ele.
                        </p>
                        <Button @click="copyCode" formato="secondary" size="xs"
                            :class="{ '!text-green-400': isCopied }">
                            {{ isCopied ? 'Código Copiado!' : campaign.invite_code }}
                        </Button>
                    </div>

                    <div class="flex flex-col p-8 bg-charcoal-d12 rounded-lg gap-4">
                        <h1 class="font-rpgSans text-sand-d6 text-2xl flex">Jogadores</h1>
                        <p class="text-sand-d6 text-xs" v-for="player in campaign.players">
                            {{ player.pivot.joined_at }}


                        </p>
                    </div>
                    <div class="flex flex-row md:flex-col gap-2 ">
                        <Button class="w-full" @click="deleteCampaign(campaign.id)" formato="ghost" size="xs">Apagar
                            campanha</Button>

                        <Button class="w-full" @click="leaveCampaign" :disabled="isLeaving" formato="ghost" size="xs">
                            {{ isLeaving ?
                                "Saindo..." : "Sair da Campanha" }}
                        </Button>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>