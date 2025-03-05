
<script setup>
import { ref } from 'vue';
import { Lock, LockOpen } from 'lucide-vue-next';
import Button from '@/Components/Button.vue';

const props = defineProps({
    inviteCode: String,
    isOpen: Boolean,
});

const emit = defineEmits(['lock']);

const isCopied = ref(false);

const copyCode = async () => {
    try {
        await navigator.clipboard.writeText(props.inviteCode);
        isCopied.value = true;
        setTimeout(() => (isCopied.value = false), 2000);
    } catch (err) {
        console.error('Falha ao copiar código:', err);
    }
};

const lockCampaignInvite = async () => {
    try {
        const newStatus = !props.campaign.is_open;
        editedCampaign.value.is_open = newStatus;
        await router.put(`/campaigns/${props.campaign.id}`, {
            is_open: editedCampaign.value.is_open,
        });
    } catch (error) {
        console.error('Erro ao alterar status da campanha:', error);
    }
};
</script>

<template>
    <div class="flex flex-col p-8 bg-charcoal-d12 rounded-lg gap-4">
        <h1 class="font-rpgSans text-sand-d6 text-2xl flex">Convite</h1>
        <p class="mt-1 text-sm text-sand-d6">
            Copie o código e envie para o jogador. Eles poderão se juntar à sua campanha com ele.
        </p>
        <Button @click="copyCode" formato="ghost" size="xs" :class="{ '!text-green-400': isCopied }">
            {{ isCopied ? 'Código Copiado!' : inviteCode }}
        </Button>
        <Button @click="lockCampaignInvite" size="xs" formato="ghost" class="text-xs" :class="isOpen ? 'text-green-400' : 'text-red-500'">
            <component :is="isOpen ? LockOpen : Lock" :size="16" />
            {{ isOpen ? 'Campanha aberta' : 'Campanha fechada' }}
        </Button>
    </div>
</template>
