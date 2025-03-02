<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from '@/Components/Button.vue';
import Modal from '@/Components/Modal.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';

import { marked } from "marked";

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

const editingTitle = ref(false);
const editingDescription = ref(false);
const editingImage = ref(false);

const editedCampaign = ref({ ...props.campaign });

watch(() => props.campaign, (newCampaign) => {
    editedCampaign.value = { ...newCampaign };
});

// Computed para o background com fallback
const backgroundStyle = computed(() => {
    return {
        backgroundImage: editedCampaign.value.image_url
            ? `url(${editedCampaign.value.image_url})`
            : 'url(/images/cover.jpg)'
    }
});

// Computed para preview de markdown
const parsedMarkdown = computed(() => {
    return marked.parse(editedCampaign.value.description || '', { sanitize: true, gfm: true, breaks: true });
});

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

// Função para salvar alterações do título
const toggleEditTitle = async () => {
    if (editingTitle.value) {
        try {
            router.put(`/campaigns/${props.campaign.id}`, {
                name: editedCampaign.value.name,
            });
            // Mensagem de sucesso pode ser exibida aqui
        } catch (error) {
            console.error('Erro ao salvar nome:', error);
        }
    }
    editingTitle.value = !editingTitle.value;
};

// Função para salvar alterações da descrição
const toggleEditDescription = async () => {
    if (editingDescription.value) {
        try {
            router.put(`/campaigns/${props.campaign.id}`, {
                description: editedCampaign.value.description,
            });
        } catch (error) {
            console.error('Erro ao salvar descrição:', error);
        }
    }
    editingDescription.value = !editingDescription.value;
};

// Função para salvar alterações da imagem
const toggleEditImage = async () => {
    if (editingImage.value) {
        useForm({ image_url: editedCampaign.value.image_url }).put(`/campaigns/${props.campaign.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                router.reload({ only: ['campaign'] });
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
            <div class="grid grid-cols-4 container gap-2 min-h-[350px] mb-8">

                <!-- Grid col-3 row-1 order 1 -->
                <div :class="campaign.is_master ? 'col-span-3' : 'col-span-4'"
                    class="flex order-1 justify-between items-center bg-cover bg-center rounded-lg w-full min-h-[350px]"
                    :style="backgroundStyle">
                    <div class="p-4 flex justify-center items-center w-full">
                        <input v-if="editingTitle" v-model="editedCampaign.name"
                            class="text-sand-d6 font-rpgSans border-solid border-0 border-b border-sand-d8 bg-transparent" />
                        <h1 class="font-rpgSans text-sand-d6 text-2xl" v-else>{{ campaign.name }}</h1>
                    </div>
                </div>

                <!-- Grid col-1 row-1 order 2 -->
                <div v-if="campaign.is_master" class="order-2 flex flex-col p-8 bg-charcoal-d12 rounded-lg gap-4">
                    <h1 class="font-rpgSans text-sand-d6 text-2xl flex">Convite</h1>
                    <p class="mt-1 text-sm text-sand-d6">
                        Copie o código e envie para o jogador. Eles poderão se juntar à sua campanha com ele.
                    </p>
                    <Button @click="copyCode" formato="secondary" size="xs" :class="{ '!text-green-400': isCopied }">
                        {{ isCopied ? 'Código Copiado!' : campaign.invite_code }}
                    </Button>
                </div>
                
                <div  v-if="!campaign.is_master" class="order-3 flex flex-col p-8 bg-charcoal-d12 rounded-lg gap-4">
                    <h1 class="font-rpgSans text-sand-d6 text-2xl flex">Personagens</h1>
                </div>

                <!-- Grid col-3 row-1 order 3 -->
                <div
                    class="col-span-3 flex flex-col p-8 bg-charcoal-d12 rounded-lg gap-2"
                    :class="{ 'order-3': campaign.is_master, 'order-2': !campaign.is_master }">
                    <div v-if="editingDescription">Formatação: # Titulo | ## Subtitulo **Negrito** | </div>
                    <textarea spellcheck="true" v-html="parsedMarkdown" v-if="editingDescription"
                        v-model="editedCampaign.description"
                        class="text-sand-d6 prose-headings:text-sand-d8 prose-headings:font-rpgSans prose-headings:font-normal prose-img:rounded-xl prose-a:text-mage-d10 prose-strong:text-sand-d6 prose-hr:border-charcoal-d8 prose-hr:my-4 hover:prose-a:text-mage-d8 scrollbar-d20 mt-1 w-full h-full p-4 bg-transparent border-none resize-none"></textarea>

                    <div v-else
                        class="text-sand-d6 prose-headings:text-sand-d8 prose-headings:font-rpgSans prose-headings:font-normal prose-img:rounded-xl prose-a:text-mage-d10 prose-strong:text-sand-d6 prose-hr:border-charcoal-d8 prose-hr:my-4 hover:prose-a:text-mage-d8 prose-code:p-2 prose-code:rounded-xl prose-code:text-charcoal-d8 prose-em:border-mage-d6"
                        v-html="parsedMarkdown"></div>
                </div>

                <!-- Grid col-1 row-1 order 4 -->
                <div v-if="campaign.is_master" class="order-4 col-span-1 bg-charcoal-d12 rounded-lg">
                    <div class="flex flex-col p-8 bg-charcoal-d12 rounded-lg gap-2">
                        <Button formato="primary" class="w-full" size="xs">Jogar agora</Button>
                        <hr class="border-charcoal-d8 my-4">
                        <Button @click="toggleEditImage" formato="secondary" size="xs">
                            {{ editingImage ? 'Salvar imagem' : 'Editar imagem' }}
                        </Button>
                        <Button @click="toggleEditTitle" formato="secondary" size="xs">
                            {{ editingTitle ? 'Salvar nome' : 'Editar nome' }}
                        </Button>
                        <Button @click="toggleEditDescription" formato="secondary" size="xs">
                            {{ editingDescription ? 'Salvar descrição' : 'Editar descrição' }}
                        </Button>
                    </div>
                </div>

                <!-- Grid col-1 row-1 order 5 -->

                <div class="order-4 col-span-3 flex p-4 text-charcoal-d8">
                    <p class="text-xs">Criado por {{ campaign.master.name }} em {{
                        dayjs(campaign.created_at).format('DD/MM/YY') }} e atualizado pela última vez {{
                            dayjs().locale(pt).to(campaign.updated_at) }}</p>
                </div>

                <!-- Grid col-1 order 5 -->

                <div class="col-span-1 order-5 flex gap-2 items-start">
                    <Button v-if="campaign.is_master" class="w-full" @click="openModal('delete')" formato="ghost"
                        size="xs">Apagar campanha</Button>

                    <Button class="w-full" @click="openModal('leave')" :disabled="campaign.players.length === 0"
                        formato="ghost" size="xs">
                        Sair da Campanha
                    </Button>
                </div>


                <Modal :show="modals.delete" @close="closeModal('delete')">
                    <div class="p-4">
                        <h1 class="font-rpgSans text-sand-d6 text-2xl">Tem certeza que deseja deletar esta
                            campanha?
                        </h1>
                        <p class="text-sm mt-2 text-red-600">Esta ação é irreversível!</p>
                        <p class="text-sand-d6 text-sm mt-2">Para confirmar, digite o nome da campanha:
                            <strong>{{
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
                        <h1 class="font-rpgSans text-sand-d6 text-2xl">Tem certeza que deseja sair desta
                            campanha?
                        </h1>
                        <p class="text-sand-d6 text-sm mt-2">Para confirmar, digite o nome da e-mail:
                        </p>

                        <input v-model="emailToLeave"
                            class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent" />

                        <div class="flex gap-2 mt-4">
                            <Button @click="closeModal('leave')" formato="secondary" size="xs">Cancelar</Button>

                            <Button @click="leaveCampaign()" formato="ghost" size="xs"
                                :disabled="$page.props.auth.user.email !== emailToLeave">Sair da
                                campanha</Button>
                        </div>
                    </div>
                </Modal>

                <Modal :show="modals.transfer" @close="closeModal('delete')">
                    <div class="p-4">
                        <h1 class="font-rpgSans text-sand-d6 text-2xl">Tem certeza que deseja deletar esta
                            campanha?
                        </h1>
                        <p class="text-sm mt-2 text-red-600">Esta ação é irreversível!</p>
                        <p class="text-sand-d6 text-sm mt-2">Para confirmar, digite o nome da campanha:
                            <strong>{{
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
    </AuthenticatedLayout>
</template>
