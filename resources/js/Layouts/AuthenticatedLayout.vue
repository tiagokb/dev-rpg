<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Button from '@/Components/Button.vue';
import { Head, Link, useForm, router, usePage } from '@inertiajs/vue3';
import { ref, watch, onMounted } from 'vue';


const viewToast = ref(true);
const toastTimeout = ref(null);


// Função para fechar o Toast manualmente
const closeToast = () => {
    viewToast.value = false;
    clearTimeout(toastTimeout.value);
};

// Observa mudanças nas mensagens flash
watch(
    () => usePage().props.flash,
    (newFlash) => {
        if (newFlash.success || newFlash.error) {
            viewToast.value = true; // Exibe o Toast quando há uma nova mensagem
            startToastTimer(); // Inicia o temporizador
        }
    },
    { deep: true }
);

// Função para iniciar o temporizador do Toast
const startToastTimer = () => {
    clearTimeout(toastTimeout.value); // Limpa qualquer temporizador existente
    toastTimeout.value = setTimeout(() => {
        viewToast.value = false; // Fecha o Toast após 5 segundos
    }, 5000);
};

// Quando a tela atualiza, vê se tem mensagens
onMounted(() => {
    if (usePage().props.flash.success || usePage().props.flash.error) {
        viewToast.value = true;
        startToastTimer();
    }
});

</script>

<template>
    <div class="min-h-screen bg-charcoal-d20 pt-2 sm:justify-center sm:pt-0">
        <div class="flex justify-center bg-charcoal-d20">
            <div class="container py-8 md:flex-row flex-col flex gap-3 justify-between items-center">

                <div class="flex gap-2">
                    <Link :href="route('campaigns.index')">
                    <ApplicationLogo class="h-14 w-14  text-gray-500" />
                    </Link>
                </div>
                <div class="flex gap-2">
                    <Link :href="route('campaigns.index')">
                    <Button size="xs">Campanhas</Button>
                    </Link>
                    <Link :href="route('profile.edit')">
                    <Button size="xs">{{ $page.props.auth.user.name }}
                    </Button>
                    </Link>

                    <Link :href="route('logout')" method="post" as="button">
                    <Button size="xs"> Sair </Button>
                    </Link>

                </div>
            </div>
        </div>

        <div v-show="viewToast && $page.props.flash.success"
            class="flex flex-col items-center gap-4 w-[300px] justify-between fixed top-40 text-sm right-8 rounded-xl bg-charcoal-d12 border border-charcoal-d10 px-4 py-4 overflow-hidden animate-in slide-in-from-right-8">
            <button @click="viewToast = false" class="flex items-center gap-2">
                <span class="text-sand-d6">{{ $page.props.flash.success }}</span>
            </button>
            <div class="w-full h-[3px] absolute bottom-0 bg-emerald-600 animate-progress" style="width: 0%;"></div>
        </div>


        <div v-show="viewToast && $page.props.flash.error"
            class="flex flex-col items-center w-[300px] justify-between fixed top-40 text-sm right-8 rounded-xl bg-charcoal-d12 border border-charcoal-d10 px-8 py-4 overflow-hidden">
            <button @click="viewToast = false" class="flex items-center justify-start gap-2">
                <span class="text-sand-d6">{{ $page.props.flash.error }}</span>
            </button>
            <div class="w-full h-[3px] absolute bottom-0 bg-red-600 animate-progress" style="width: 0%;"></div>
        </div>

        <slot />
    </div>
</template>