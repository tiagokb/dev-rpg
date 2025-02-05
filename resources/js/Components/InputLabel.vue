<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label for="email" class="font-rpgSans text-sand-d8 text-xs font-thin"><span v-if="value">{{ value }}</span>
        <span v-else><slot /></span></label>
</template>
