<template>
    <div
      class="tooltip-wrapper"
      @mouseenter="showTooltip"
      @mouseleave="hideTooltip"
    >
      <!-- Conteúdo "alvo" onde o usuário passa o mouse -->
      <slot></slot>
      <!-- Tooltip que aparece quando o mouse está sobre o elemento -->
      <div v-if="visible" class="tooltip" :class="position">
        {{ message }}
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const props = defineProps({
    message: {
      type: String,
      required: true
    },
    position: {
      type: String,
      default: 'top' // opções: top, bottom, left, right
    }
  });
  
  const visible = ref(false);
  
  const showTooltip = () => {
    visible.value = true;
  };
  
  const hideTooltip = () => {
    visible.value = false;
  };
  </script>
  
  <style scoped>
  .tooltip-wrapper {
    position: relative;
    display: inline-block;
  }
  
  /* Estilo base para a tooltip */
  .tooltip {
    position: absolute;
    background-color: #333;
    color: #fff;
    padding: 6px 8px;
    border-radius: 4px;
    white-space: nowrap;
    font-size: 0.85rem;
    z-index: 10;
    opacity: 0;
    transition: opacity 0.2s ease-in-out;
    pointer-events: none;
  }
  
  /* Exibe a tooltip quando o elemento estiver com hover */
  .tooltip-wrapper:hover .tooltip {
    opacity: 1;
  }
  
  /* Posições da tooltip */
  .tooltip.top {
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    margin-bottom: 8px;
  }
  
  .tooltip.bottom {
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    margin-top: 8px;
  }
  
  .tooltip.left {
    right: 100%;
    top: 50%;
    transform: translateY(-50%);
    margin-right: 8px;
  }
  
  .tooltip.right {
    left: 100%;
    top: 50%;
    transform: translateY(-50%);
    margin-left: 8px;
  }
  </style>
  