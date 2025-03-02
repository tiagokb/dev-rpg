<script setup>
import IconMaster from '@/Components/IconMaster.vue';
import Button from '@/Components/Button.vue';
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import { marked } from 'marked';

const props = defineProps({
    campaignsData: Object
});

</script>

<template>
    <div class="bg-charcoal-d12 outline outline-1 outline-charcoal-d10 rounded-lg col-1">
        <Link :href="route('campaigns.view', campaignsData.id)">
        <div class="h-48 bg-cover bg-center rounded-lg p-2 flex flex-col gap-4 justify-center items-center"
            :style="campaignsData.image_url ? { backgroundImage: `url(${campaignsData.image_url})` } : { backgroundImage: `url(/images/cover.jpg)` }">
            <IconMaster class="" v-if="campaignsData.is_master" />
            <h2 class="font-rpgSans text-2xl text-sand-d8 text-center">{{ campaignsData.name }}</h2>
            <h3 class="font-rpgSans text-xl text-sand-d8 text-center">{{ campaignsData.subname }}</h3>
        </div>
        </Link>

        <div class="p-6 text-sand-d6">
            <p class="line-clamp-3 text-xs" v-html="marked.parse(campaignsData.description || '', { sanitize: true, breaks: false })">
            </p>
            <div class="mt-4 flex gap-2">
                <Link class="w-full" :href="route('campaigns.view', campaignsData.id)">
                <Button formato="ghost" size="xs" class="w-full">Detalhes</Button>
                </Link>
                <Button formato="primary" size="xs" class="w-full">Jogar agora</Button>
            </div>
        </div>
    </div>
</template>