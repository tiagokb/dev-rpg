<script setup>
import IconMaster from '@/Components/IconMaster.vue';
import Button from '@/Components/Button.vue';
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
    campaignsData: Object
});

</script>

<template>

    <div class="bg-charcoal-d12 outline outline-1 outline-charcoal-d10 rounded-lg col-1">
        <Link :href="route('campaigns.view', campaignsData.id)">
        <div class="h-48 bg-cover bg-center rounded-lg p-2"
            :style="campaignsData.image_url ? { backgroundImage: `url(${campaignsData.image_url})` } : { backgroundImage: `url(/images/cover.jpg)` }">
            <IconMaster v-if="campaignsData.is_master" />

        </div>
        </Link>

        <div class="p-6 text-sand-d6">
            <h2 class="font-rpgSans text-sand-d8">{{ campaignsData.name }}</h2>
            <p class="text-sand-d6 text-sm line-clamp-3"> {{ campaignsData.description }}
            </p>
            <div class="mt-4 flex gap-2">
                <Link class="w-full" :href="route('campaigns.view', campaignsData.id)">
                <Button formato="ghost" size="xs" class="w-full">Ver</Button>
                </Link>
                <Button formato="primary" size="xs" class="w-full">Iniciar</Button>
            </div>
        </div>
    </div>
</template>