<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from '@/Components/Button.vue';
import Modal from '@/Components/Modal.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

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

const editedCampaign = ref({ ...props.campaign });
const editingInfos = ref(false);
const editingImage = ref(false);

const campaignNameToDelete = ref('');
const emailToLeave = ref('');

const newMaster = ref(null);

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

const leaveCampaign = () => {

    router.put(route('campaigns.leave', props.campaign.id))
    closeModal('leave')
}

const transferCampaign = async () => {
    router.put(route('campaigns.transfer', props.campaign.id), {
        new_master_id: newMaster.value
    })
    closeModal('leave')
}

const deleteCampaign = (campaignId) => {
    router.delete(route('campaigns.destroy', campaignId));
};

const modals = ref({
    delete: false,
    leave: false
});

const openModal = (type) => (modals.value[type] = true);

const closeModal = (type) => {
    modals.value[type] = false;
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

                        <Button v-if="campaign.is_master" @click="toggleEditInfos" formato="secondary" size="icon">
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
                        <p class="text-sand-d6 text-xs">Criado por {{ campaign.master.name }} em {{
                            dayjs(campaign.created_at).format('DD/MM/YY') }} e atualizado pela última vez {{
                                dayjs().locale(pt).to(campaign.updated_at) }}</p>
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
                            {{ player.name }} entrou na campanha {{
                                dayjs().locale(pt).to(dayjs(player.pivot.joined_at).toISOString()) }}
                        </p>
                    </div>
                    <div class="flex flex-row md:flex-col gap-2 ">
                        <Button v-if="campaign.is_master" class="w-full" @click="openModal('delete')" formato="ghost"
                            size="xs">Apagar campanha</Button>

                        <Button class="w-full" @click="openModal('leave')" :disabled="campaign.players.length === 0"
                            formato="ghost" size="xs">
                            Sair da Campanha
                        </Button>
                    </div>

                    <Modal :show="modals.delete" @close="closeModal('delete')">
                        <div class="p-4">
                            <h1 class="font-rpgSans text-sand-d6 text-2xl">Tem certeza que deseja deletar esta campanha?
                            </h1>
                            <p class="text-sm mt-2 text-red-600">Esta ação é irreversível!</p>
                            <p class="text-sand-d6 text-sm mt-2">Para confirmar, digite o nome da campanha: <strong>{{
                                campaign.name }}</strong>
                            </p>
                            <input v-model="campaignNameToDelete"
                                class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent" />

                            <div class="flex gap-2 mt-4">
                                <Button @click="closeModal('delete')" formato="secondary" size="xs">Cancelar</Button>

                                <Button @click="deleteCampaign(campaign.id)" formato="ghost" size="xs"
                                    :disabled="campaignNameToDelete !== campaign.name">Deletar</Button>
                            </div>
                        </div>
                    </Modal>

                    <Modal :show="modals.leave" @close="closeModal('leave')">
                        <div v-if="campaign.is_master">
                            <div class="p-4">
                                <h1 class="font-rpgSans text-sand-d6 text-2xl">Você é o mestre desta campanha!</h1>
                                <p class="text-sand-d6 text-sm mt-2">Para sair da campanha, você deve transferir o
                                    mestre para outro jogador.</p>

                                <p class="text-sand-d6 text-sm mt-2">Selecione o novo mestre:</p>
                                <select v-model="newMaster"
                                    class="mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent text-white">
                                    <option class="text-black" v-for="player in campaign.players" :value="player.id">{{
                                        player.name }}
                                    </option>
                                </select>

                                <div class="flex gap-2 mt-4">
                                    <Button @click="closeModal('leave')" formato="secondary" size="xs">Cancelar</Button>
                                    <Button @click="transferCampaign()" formato="ghost" size="xs">Transferir
                                        mestre</Button>
                                </div>
                            </div>
                        </div>

                        <div v-else class="p-4">
                            <h1 class="font-rpgSans text-sand-d6 text-2xl">Tem certeza que deseja sair desta campanha?
                            </h1>
                            <p class="text-sand-d6 text-sm mt-2">Para confirmar, digite o nome da e-mail:
                            </p>

                            <input v-model="emailToLeave"
                                class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent" />

                            <div class="flex gap-2 mt-4">
                                <Button @click="closeModal('leave')" formato="secondary" size="xs">Cancelar</Button>

                                <Button @click="leaveCampaign()" formato="ghost" size="xs"
                                    :disabled="$page.props.auth.user.email !== emailToLeave">Sair da campanha</Button>
                            </div>
                        </div>
                    </Modal>

                    <Modal :show="modals.transfer" @close="closeModal('delete')">
                        <div class="p-4">
                            <h1 class="font-rpgSans text-sand-d6 text-2xl">Tem certeza que deseja deletar esta campanha?
                            </h1>
                            <p class="text-sm mt-2 text-red-600">Esta ação é irreversível!</p>
                            <p class="text-sand-d6 text-sm mt-2">Para confirmar, digite o nome da campanha: <strong>{{
                                campaign.name }}</strong>
                            </p>
                            <input v-model="campaignNameToDelete"
                                class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent" />

                            <div class="flex gap-2 mt-4">
                                <Button @click="closeModal('delete')" formato="secondary" size="xs">Cancelar</Button>

                                <Button @click="deleteCampaign(campaign.id)" formato="ghost" size="xs"
                                    :disabled="campaignNameToDelete !== campaign.name">Deletar</Button>
                            </div>
                        </div>
                    </Modal>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>