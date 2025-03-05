<script setup>
/**********************************
 * IMPORTAÇÕES E CONFIGURAÇÕES INICIAIS 
 **********************************/

// Vue e Inertia
import { ref, watch, computed } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';

// Componentes UI
import IconMaster from '@/Components/IconMaster.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from '@/Components/Button.vue';
import Modal from '@/Components/Modal.vue';

// Processamento de Markdown
import { marked } from "marked";

// Ícones e Utilitários
import { Lock, LockOpen, UserRoundMinus } from 'lucide-vue-next';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import pt from 'dayjs/locale/pt-br';
import utc from 'dayjs/plugin/utc';

// Configuração DayJS
dayjs.extend(relativeTime);
dayjs.extend(utc);
dayjs.locale(pt);

/**********************************
 * PROPS E ESTADOS REATIVOS 
 **********************************/

const props = defineProps({
    campaign: Object,
});

// Estados de UI e Modais
const modals = ref({
    delete: false,
    leave: false,
    addItem: false,
    removePlayer: false,
    confirmation: false
});

const selectedTab = ref('description');
const editingCampaign = ref(false);
const editingDescription = ref(false);

// Dados da Campanha
const editedCampaign = ref({ ...props.campaign });
const removePlayerData = ref(null);
const newMaster = ref(null);
const campaignNameConfirm = ref('');

// Controle de Imagem
const imageLoaded = ref(false);
const imageError = ref(false);

// Formulário de Itens
const formAddItem = useForm({
    campaign_id: props.campaign.id,
    name: 'Arco Recurvo',
    classification: 'Arma de longo alcance',
    description: 'Um arco feito de madeira de ébano, com detalhes em prata e corda de seda.',
    damage: '8d8 perfurante + 1d8 elétrico + 1d8 fogo + 1d8 gélido + 1d8 ácido',
    magical_properties: 'O arco é capaz de disparar flechas mágicas que causam dano adicional.',
    classes: 'Ranger, Ladino',
    weight: 2,
    image_url: '/items/bow_vintage.png',
    rarity: 'Lendário',
    type: 'Arma',
    price: 1200,
});

/**********************************
 * COMPUTED PROPERTIES 
 **********************************/

// Estilo dinâmico do background
const backgroundStyle = computed(() => ({
    backgroundImage: editedCampaign.value.cover_img_url
        ? `url(${editedCampaign.value.cover_img_url})`
        : 'url(/images/cover.jpg)'
}));

// Renderização de markdown
const parsedMarkdown = computed(() =>
    marked.parse(editedCampaign.value.description || '', {
        sanitize: true,
        breaks: true,
    })
);

// Ordenação de itens
const sortedItems = computed(() =>
    [...props.campaign.items].sort((a, b) =>
        b.created_at.localeCompare(a.created_at)
    )
);


/**********************************
 * WATCHERS 
 **********************************/

// Atualiza estado da campanha
watch(() => props.campaign, (newCampaign) => {
    editedCampaign.value = { ...newCampaign };
});

// Verificação de URL da imagem
watch(
    () => editedCampaign.value.cover_img_url,
    (newUrl) => {
        const img = new Image();
        img.onload = () => {
            imageLoaded.value = true;
            imageError.value = false;
        };
        img.onerror = () => {
            imageLoaded.value = false;
            imageError.value = true;
        };
        img.src = newUrl;
    },
    { immediate: true }
);

/**********************************
 * FUNÇÕES PRINCIPAIS 
 **********************************/

/*-------- Gestão de Modais --------*/
const openModal = (type) => modals.value[type] = true;
const closeModal = (type) => modals.value[type] = false;

/*-------- Gestão de Itens --------*/
const submitItem = () => {
    formAddItem.post(route('items.store'), {
        onSuccess: () => closeModal('addItem')
    });
};

/*-------- Processamento de Dano --------*/
const parseDamage = (damage) => {
    if (!damage) return [];
    if (Array.isArray(damage)) return damage;
    return typeof damage === 'string'
        ? damage.split(/[+,;]/).map(part => part.trim())
        : [];
};

const getDamageClasses = (damageText) => {
    const text = damageText.toLowerCase();
    return {
        'bg-red-500/20 text-red-500': text.includes('fogo') || text.includes('queimadura'),
        'bg-blue-600/20 text-blue-400': text.includes('gélido') || text.includes('gelo'),
        'bg-sky-500/20 text-sky-500': text.includes('raio') || text.includes('elétrico'),
        'bg-lime-500/20 text-lime-500': text.includes('ácido'),
        'bg-purple-500/20 text-purple-500': text.includes('mágico'),
        'bg-stone-500/20 text-stone-500': text.includes('perfurante'),
        'bg-zinc-500/20 text-zinc-500': text.includes('concussão') || text.includes('concussivo'),
        'bg-gray-500/20 text-gray-500': true // Fallback
    };
};

const isCopied = ref(false);
let timeoutId = null;

const changeTab = (tab) => {
    selectedTab.value = tab;
};

const showFeedbackCode = () => {
    isCopied.value = true;
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => {
        isCopied.value = false;
    }, 2000);
};

const copyCode = async () => {
    try {
        await navigator.clipboard.writeText(props.campaign.invite_code);
        showFeedbackCode();
    } catch (err) {
        console.error('Falha ao copiar código:', err);
        try {
            const textarea = document.createElement('textarea');
            textarea.value = props.campaign.invite_code;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            showFeedbackCode();
        } catch (fallbackError) {
            console.error('Falha no fallback:', fallbackError);
            alert('Não foi possível copiar o código. Tente novamente.');
        }
    }
};

const toggleEditCampaign = async () => {
    if (editingCampaign.value) {
        try {
            router.put(`/campaigns/${props.campaign.id}`, {
                title: editedCampaign.value.title,
                subtitle: editedCampaign.value.subtitle,
                cover_img_url: editedCampaign.value.cover_img_url,
            });
        } catch (error) {
            console.error('Erro ao salvar:', error);
        }
    }
    editingCampaign.value = !editingCampaign.value;
};

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

const lockCampaignInvite = async () => {
    try {
        const newStatus = !props.campaign.is_open;
        editedCampaign.value.is_open = newStatus;
        router.put(`/campaigns/${props.campaign.id}`, {
            is_open: editedCampaign.value.is_open,
        });
    } catch (error) {
        console.error('Erro ao alterar status da campanha:', error);
    }
};

const removePlayerModal = (playerName, playerId) => {
    removePlayerData.value = {
        playerName,
        playerId
    };
    openModal('removePlayer')
};

const leaveCampaign = () => {
    router.put(route('campaigns.leave', props.campaign.id));
    closeModal('leave');
};

const transferCampaign = async () => {
    router.put(route('campaigns.transfer', props.campaign.id), {
        new_master_id: newMaster.value,
    });
    closeModal('leave');
};

const deleteCampaign = (campaignId) => {
    router.delete(route('campaigns.destroy', campaignId));
};

const removePlayerCampaign = (playerId) => {
    router.delete(route('campaigns.removePlayer', { campaign: props.campaign.id, player: playerId }));
    closeModal('removePlayer');
};

const deleteItem = (itemId) => {
    router.delete(route('items.destroy', itemId));
};


</script>

<template>

    <Head title="Campanhas" />
    <AuthenticatedLayout>
        <div class="flex justify-center">
            <div class="grid grid-cols-4 container gap-2 min-h-[350px] mb-8">

                <!-- Área de capa com background (Grid Col-2 e Order-1)-->
                <!--  Ações de Editar (Apenas para o Mestre) Grid Col-1 Order-3 -->

                <div :class="campaign.is_master ? 'col-span-3' : 'col-span-3'"
                    class="relative flex order-2 justify-between items-center bg-cover bg-center rounded-lg w-full min-h-[300px]"
                    :style="backgroundStyle">
                    <IconMaster class="absolute top-2 left-2 z-10" v-if="campaign.is_master" />

                    <div class="px-8 py-4 flex justify-between items-center w-full">
                        <div class="absolute inset-0 bg-black opacity-50 rounded-lg"></div>
                        <div v-if="editingCampaign" class="flex flex-col gap-4">
                            <input v-model="editedCampaign.title"
                                class="text-sand-d6 z-10 font-rpgSans border-solid border-0 border-b border-sand-d8 bg-transparent" />
                            <input v-model="editedCampaign.subtitle"
                                class="text-sand-d6 z-10 font-rpgSans border-solid border-0 border-b border-sand-d8 bg-transparent" />
                            <input v-model="editedCampaign.cover_img_url"
                                class="text-sand-d6 z-10 font-rpgSans border-solid border-0 border-b border-sand-d8 bg-transparent" />

                        </div>
                        <div v-else class="flex flex-col justify-center">
                            <h2 class="relative z-10 font-rpgSans text-2xl text-sand-d8">
                                {{ campaign.title }}
                            </h2>
                            <h3 class="relative z-10 font-rpgSans text-sm text-sand-d8">
                                {{ campaign.subtitle }}
                            </h3>
                        </div>


                        <!-- Indicador de status da imagem -->
                        <div v-if="imageError" class="text-red-500 text-sm mt-2">
                            Ops! Não foi possível carregar a imagem.
                        </div>
                        <div v-else-if="!imageLoaded" class="text-gray-500 text-sm mt-2">
                            Carregando imagem...
                        </div>

                        <div v-if="campaign.is_master" class="order-3 z-10 bg-charcoal-d12 rounded-lg">
                            <div class="flex flex-col p-8 bg-charcoal-d12 rounded-lg gap-2">
                                <Button formato="primary" class="w-full" size="xs">Jogar agora</Button>
                                <hr class="border-charcoal-d8 my-4">
                                <Button @click="toggleEditCampaign" formato="ghost" size="xs">
                                    {{ editingCampaign ? 'Salvar' : 'Editar campanha' }}
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Convite e Campanha Lock (Grid Col-1 Order-2)-->
                <div v-if="campaign.is_master" class="order-1 flex flex-col p-8 bg-charcoal-d12 rounded-lg gap-4">
                    <h1 class="font-rpgSans text-sand-d6 text-2xl flex">Convite</h1>
                    <p class="mt-1 text-sm text-sand-d6">
                        Copie o código e envie para o jogador. Eles poderão se juntar à sua campanha com ele.
                    </p>
                    <Button @click="copyCode" formato="ghost" size="xs" :class="{ '!text-green-400': isCopied }">
                        {{ isCopied ? 'Código Copiado!' : campaign.invite_code }}
                    </Button>
                    <Button @click="lockCampaignInvite" size="xs" formato="ghost" class="text-xs"
                        :fontType="campaign.is_open ? 'active' : 'danger'"
                        :class="campaign.is_open ? 'text-green-400' : 'text-red-500'">
                        <component :is="campaign.is_open ? LockOpen : Lock" :size="16" />
                        {{ campaign.is_open ? 'Campanha aberta' : 'Campanha fechada' }}
                    </Button>
                </div>

                <!-- Personagens (Apenas para jogadores) Order-3 -->
                <div v-if="!campaign.is_master" class="order-3 flex flex-col p-8 bg-charcoal-d12 rounded-lg gap-4">
                    <h1 class="font-rpgSans text-sand-d6">Personagens</h1>
                    <Button formato="primary" class="w-full" size="xs">Jogar agora</Button>
                </div>

                <div class="order-4 flex flex-col gap-1 col-span-3">

                    <div class="flex items-center gap-4 bg-charcoal-d12 p-4 rounded-lg" v-if="campaign.is_master">
                        <Button @click="changeTab('description')"
                            :formato="selectedTab === 'description' ? 'primary' : 'ghost'" size="xs">Descrição</Button>
                        <Button @click="changeTab('npcs')" :formato="selectedTab === 'npcs' ? 'primary' : 'ghost'"
                            size="xs">NPC's</Button>
                        <Button @click="changeTab('items')" :formato="selectedTab === 'items' ? 'primary' : 'ghost'"
                            size="xs">Itens</Button>
                        <Button @click="changeTab('notes')" :formato="selectedTab === 'notes' ? 'primary' : 'ghost'"
                            size="xs">Notas</Button>
                    </div>

                    <!-- Descrição da Campanha (Grid Col-3 Order-4) -->
                    <div class="col-span-3 flex flex-col p-8 bg-charcoal-d12 rounded-lg gap-2">
                        <div class="flex flex-col gap-4" v-if="selectedTab === 'description'">
                            <Button @click="toggleEditDescription" formato="ghost" size="xs" class="w-fit"
                                v-if="campaign.is_master">{{
                                    editingDescription ? 'Salvar' : 'Editar descrição' }}</Button>

                            <textarea v-if="editingDescription" v-model="editedCampaign.description"
                                class="text-sand-d6 bg-charcoal-d20 prose markdown scrollbar-d20 mt-1 w-full h-full p-4 max-h-[450px] overflow-y-auto resize-none"></textarea>
                            <div v-else class="prose markdown" v-html="parsedMarkdown"></div>
                            <div v-if="editingDescription" v-html="parsedMarkdown"
                                class="text-sand-d6  prose markdown scrollbar-d20 mt-1 w-full h-full p-4 max-h-[450px] overflow-y-auto">
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4" v-if="selectedTab === 'items'">
                            <div class="col-span-3">
                                <Button @click="openModal('addItem')" formato="ghost" size="xs" class="w-fit"
                                    v-if="campaign.is_master">Criar novo item</Button>
                            </div>

                            <div v-for="item in sortedItems" :key="item.id"
                                class="flex flex-col px-4 py-4 gap-4 border border-charcoal-d10 rounded-lg justify-between">

                                <div class="grid grid-cols-6">
                                    <img :src="item.image_url" alt="Imagem do item"
                                        class="col-span-2 w-16 h-16 rounded-lg bg-charcoal-d20/50 p-1 flex items-center justify-center " />
                                    <div class="col-span-4">
                                        <h1 class="font-rpgSans text-sm flex flex-col gap-2" :class="item.rarity === 'Lendário' ? 'text-amber-400' :
                                            item.rarity === 'Raro' ? 'text-purple-400' :
                                                item.rarity === 'Incomum' ? 'text-green-400' : 'text-gray-400'">
                                            {{ item.name }}
                                            <span class="text-charcoal-d6 text-xs"> {{ item.classification }}</span>
                                        </h1>
                                    </div>
                                </div>

                                <p class="text-charcoal-d4 text-xs">
                                    {{ item.type }}
                                </p>

                                <p class="text-xs text-charcoal-d8">Descrição:
                                <p class="text-charcoal-d4 text-xs">
                                    {{ item.description }}
                                </p>
                                </p>

                                <div class="flex gap-2 items-center flex-wrap">
                                    <span v-if="item.damage" v-for="(damagePart, index) in parseDamage(item.damage)"
                                        :key="index" class="px-2 py-1 w-fit rounded-md text-xs font-medium"
                                        :class="getDamageClasses(damagePart)">
                                        {{ damagePart }}
                                    </span>
                                    <span v-else class="text-gray-500 text-xs">Sem dano</span>
                                </div>

                                <hr class="border-charcoal-d10 border-1 w-full">

                                <p class="text-xs text-charcoal-d8">Propriedades:
                                <p class="text-charcoal-d4 text-xs">
                                    {{ item.magical_properties }}
                                </p>
                                </p>

                                <hr class="border-charcoal-d10 border-1 w-full">

                                <div class="flex gap-4 text-left w-full">
                                    <p class="text-charcoal-d6 text-xs ">
                                    <p class="text-xs text-charcoal-d8">Serve para:</p>
                                    {{ item.classes }}
                                    </p>
                                    <p class="text-charcoal-d6 text-xs">
                                    <p class="text-xs text-charcoal-d8">Peso:</p>
                                    {{ item.weight }}
                                    </p>
                                </div>
                                <div class="flex gap-2 items-center justify-between">
                                    <Button formato="ghost" size="xxs"> Editar item </Button>
                                    <Button formato="ghost" size="xxs"> Dar item </Button>
                                    <Button @click="deleteItem(item.id)" formato="ghost" size="xxs"> Apagar item
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Jogadores (Col-1 Order-5) -->
                <div class="order-5 col-span-1 flex flex-col p-8 bg-charcoal-d12 rounded-lg gap-4">
                    <h1 class="font-rpgSans text-sand-d6 flex justify-between">Jogadores <span class="text-xs text-charcoal-d6 font-sans">{{campaign.players.length}} / {{campaign.max_players}}</span></h1>
                    <div v-for="player in campaign.players" :key="player.id"
                        class="flex justify-between items-center pb-2 border-b border-0 border-charcoal-d10">
                        <div class="flex flex-col">
                            <p class="text-sand-d6 text-xs">{{ player.name }}</p>
                            <span class="text-sand-d6 text-xs">{{ dayjs().locale(pt).to(player.pivot.joined_at)
                            }}</span>
                        </div>
                        <Button @click="removePlayerModal(player.name, player.id)" formato="ghost" size="xs"
                            v-if="campaign.is_master">
                            <UserRoundMinus :size="12" />
                        </Button>
                    </div>
                </div>

                <!-- Informações da Campanha (Col-3 Order-5) -->
                <div class="order-5 col-span-3 flex p-4 text-charcoal-d8">
                    <p class="text-xs">Criado por {{ campaign.master.name }} em {{
                        dayjs(campaign.created_at).format('DD/MM/YY') }} e atualizado pela última vez {{
                            dayjs().locale(pt).to(campaign.updated_at) }}</p>

                </div>

                <!-- Ações de Deletar e Sair (Col-1 Order-6) -->
                <div class="col-span-1 order-6 flex flex-col gap-2 items-start">
                    <Button v-if="campaign.is_master" class="w-full" @click="openModal('delete')" formato="secondary"
                        size="xs">Apagar
                        campanha</Button>

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
                                campaign.title }}</strong>
                        </p>
                        <input v-model="campaignNameConfirm"
                            class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent" />

                        <div class="flex gap-2 mt-4">
                            <Button @click="closeModal('delete')" formato="secondary" size="xs">Cancelar</Button>

                            <Button @click="deleteCampaign(campaign.id)" formato="ghost" size="xs"
                                :disabled="campaignNameConfirm !== campaign.title">Deletar</Button>
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
                        <p class="text-sand-d6 text-sm mt-2">Para confirmar, digite o nome da campanha:
                        </p>

                        <input v-model="campaignNameConfirm"
                            class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent" />

                        <div class="flex gap-2 mt-4">
                            <Button @click="closeModal('leave')" formato="secondary" size="xs">Cancelar</Button>

                            <Button @click="leaveCampaign()" formato="ghost" size="xs"
                                :disabled="campaignNameConfirm !== campaign.title">Sair da
                                campanha</Button>
                        </div>
                    </div>
                </Modal>

                <Modal :show="modals.removePlayer" @close="closeModal('removePlayer')">
                    <div class="p-4">
                        <h1 class="font-rpgSans text-sand-d6 text-2xl">Você tem certeza que deseja remover {{
                            removePlayerData.playerName }} da campanha {{ campaign.title }}?</h1>

                        <p class="text-sm mt-2 text-red-600">Esta ação é irreversível!</p>
                        <p class="text-sand-d6 text-sm mt-2">Para confirmar, digite o nome da campanha:</p>

                        <input v-model="campaignNameConfirm"
                            class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent" />

                        <div class="flex gap-2 mt-4">
                            <Button @click="closeModal('removePlayer')" formato="secondary" size="xs">Cancelar</Button>

                            <Button @click="removePlayerCampaign(removePlayerData.playerId)" formato="ghost" size="xs"
                                :disabled="campaignNameConfirm !== campaign.title">Deletar</Button>
                        </div>
                    </div>
                </Modal>

                <Modal :show="modals.addItem" @close="closeModal('addItem')" :warningText="Texteeee">
                    <div class="p-4">
                        <form @submit.prevent="submitItem" class="flex flex-col gap-4">
                            <header>
                                <h1 class="font-rpgSans text-white text-2xl">Criar novo item</h1>
                                <p class="mt-1 text-sm text-sand-d6">
                                    Configure o item para salvar na sua campanha
                                </p>
                            </header>

                            <div>
                                <label class="font-rpgSans text-sand-d8 text-xs font-thin">Nome do Item</label>
                                <input v-model="formAddItem.name" type="text"
                                    class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent">
                            </div>
                            <div>
                                <label class="font-rpgSans text-sand-d8 text-xs font-thin">Classificação</label>
                                <input v-model="formAddItem.classification" type="text"
                                    class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent">
                            </div>
                            <div>
                                <label class="font-rpgSans text-sand-d8 text-xs font-thin">Descrição</label>
                                <textarea v-model="formAddItem.description"
                                    class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent"></textarea>
                            </div>
                            <div>
                                <label class="font-rpgSans text-sand-d8 text-xs font-thin">Dano</label>
                                <input v-model="formAddItem.damage" type="text"
                                    class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent">
                            </div>
                            <div>
                                <label class="font-rpgSans text-sand-d8 text-xs font-thin">Propriedades Mágicas</label>
                                <input v-model="formAddItem.magical_properties" type="text"
                                    class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent">
                            </div>
                            <div>
                                <label class="font-rpgSans text-sand-d8 text-xs font-thin">Classes</label>
                                <input v-model="formAddItem.classes" type="text"
                                    class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent">
                            </div>
                            <div>
                                <label class="font-rpgSans text-sand-d8 text-xs font-thin">Peso</label>
                                <input v-model="formAddItem.weight" type="text"
                                    class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent">
                            </div>
                            <div>
                                <label class="font-rpgSans text-sand-d8 text-xs font-thin">URL da Imagem</label>
                                <input v-model="formAddItem.image_url" type="text"
                                    class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent">
                            </div>
                            <div>
                                <label class="font-rpgSans text-sand-d8 text-xs font-thin">Raridade</label>
                                <select v-model="formAddItem.rarity"
                                    class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent">
                                    <option value="Comum">Comum</option>
                                    <option value="Incomum">Incomum</option>
                                    <option value="Raro">Raro</option>
                                    <option value="Lendário">Lendário</option>
                                </select>
                            </div>
                            <div>
                                <label class="font-rpgSans text-sand-d8 text-xs font-thin">Tipo</label>
                                <select v-model="formAddItem.type"
                                    class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent">
                                    <option value="Arma">Arma</option>
                                    <option value="Armadura">Armadura</option>
                                    <option value="Poção">Poção</option>
                                    <option value="Anel">Anel</option>
                                    <option value="Varinha">Varinha</option>
                                    <option value="Pergaminho">Pergaminho</option>
                                    <option value="Outro">Outro</option>
                                </select>
                            </div>
                            <div>
                                <label class="font-rpgSans text-sand-d8 text-xs font-thin">Preço</label>
                                <input v-model="formAddItem.price" type="number"
                                    class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent">
                            </div>

                            <div class="flex gap-2 items-center">
                                <Button formato="primary" size="xs" type="submit">Criar item</Button>
                                <Button formato="secondary" size="xs" @click="closeModal('addItem')">Cancelar</Button>
                            </div>
                        </form>
                    </div>
                </Modal>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
