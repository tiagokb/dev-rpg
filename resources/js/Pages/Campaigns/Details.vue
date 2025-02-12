<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    campaign: Object,
});

</script>
<template>

    <Head title="Campanhas" />
    <AuthenticatedLayout>

        <div class="flex justify-center">
            <div class="container grid grid-cols-4 gap-2 min-h-[350px] ">
                <div class="grid col-span-4 justify-between items-center bg-cover bg-center rounded-lg h-80 w-full"
                :style="{
                    backgroundImage: campaign.image_url ? `url(${campaign.image_url})` : 'url(/images/cover.jpg)'
                }">>
                </div>
                <div class="grid col-span-3 p-8 bg-charcoal-d12 rounded-lg gap-5">
                    <h1 class="font-rpgSans text-sand-d6 text-2xl">Detalhes</h1>
                    <p class=" text-sand-d6">{{ campaign.description }}</p>
                </div>
                <div class="grid col-span-1 p-8 bg-charcoal-d12 rounded-lg gap-5">
                    <h1 class="font-rpgSans text-sand-d6 text-2xl">Jogadores</h1>
                    <p class=" text-sand-d6">Criado por: {{ campaign.master.name }}</p>
                    <p class=" text-sand-d6">Jogadores: {{ campaign.players }}</p>
                </div>
            </div>
        </div>


    </AuthenticatedLayout>
</template>