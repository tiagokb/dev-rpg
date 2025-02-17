<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from '@/Components/Button.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { FilePenLine, Save } from 'lucide-vue-next';

const props = defineProps({
    campaign: Object,
});

const isCopied = ref(false);
let timeoutId = null;


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

const editedCampaign = ref({ ...props.campaign });
const editingInfos = ref(false);
const editingImage = ref(false);

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
</script>

<template>

    <Head title="Campanhas" />
    <AuthenticatedLayout>
        <div class="flex justify-center">
            <div class="container grid grid-cols-4 gap-2 min-h-[350px]">
                <div class="grid col-span-4 grid-cols-5 justify-between items-center bg-cover bg-center rounded-lg h-80 w-full"
                    :style="{
                        backgroundImage: editedCampaign.image_url ? `url(${editedCampaign.image_url})` : 'url(/images/cover.jpg)'
                    }">
                    <div class="p-4 flex flex-col gap-4 col-span-1 col-start-5">

                        <label v-if="editingImage" for="editImage"
                            class="font-rpgSans text-sand-d8 text-xs font-thin">Digite o URL da imagem</label>

                        <input v-if="editingImage"
                            class="text-sand-d6 mt-1 block w-full border-solid border-0 border-b border-sand-d8 bg-transparent"
                            id="editImage" type="text" v-model="editedCampaign.image_url">

                        <Button @click="toggleEditImage" formato="secondary" size="xs"> {{ editingImage ? 'Salvar' :
                            'Trocar imagem' }} </Button>
                    </div>
                </div>
                <div class="flex flex-col col-span-2 p-8 bg-charcoal-d12 rounded-lg gap-4 items-start">
                    <h1 class="font-rpgSans text-sand-d6 text-2xl">Ações</h1>
                    <Button formato="primary" class="w-full" size="xs">Iniciar</Button>
                    <p class="text-sand-d6">Criado por: {{ campaign.master.name }}</p>

                </div>
                <div class="grid col-span-2 p-8 bg-charcoal-d12 rounded-lg gap-5 flex flex-col">
                    <h1 class="font-rpgSans text-sand-d6 text-2xl">Jogadores</h1>
                    <Button  @click="copyCode" formato="secondary" size="xs" :class="{ '!text-green-400': isCopied }">
                        {{ isCopied ? 'Código Copiado!' : campaign.invite_code }}
                    </Button>
                    <p class="text-sand-d6">Jogadores: {{ campaign.players }}</p>

                </div>
                <div class="grid col-span-4 p-8 bg-charcoal-d12 rounded-lg gap-5">
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
            </div>
        </div>
    </AuthenticatedLayout>
</template>