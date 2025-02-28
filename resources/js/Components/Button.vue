<template>
    <button
      :type="htmlType"
      :class="buttonClasses"
      class="flex justify-center gap-2 items-center rounded-md font-rpgSans text-sand-d6 transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed"
    >
      <slot />
    </button>
  </template>
  
  <script setup>
  import { computed } from 'vue'
  
  const props = defineProps({

    formato: {
      type: String,
      default: 'secondary',
      validator: (value) => ['primary', 'secondary', 'ghost'].includes(value)
    },

    size: {
      type: String,
      default: 'normal',
      validator: (value) => ['normal', 'xs', 'icon'].includes(value)
    },

    htmlType: {
      type: String,
      default: 'button'
    }
  })
  
  const buttonClasses = computed(() => {
    return [
      typeClasses[props.formato],
      sizeClasses[props.size],
    ]
  })
  
  const typeClasses = {
    primary: 'bg-mage-d10 text-white hover:bg-mage-d8 hover:outline hover:outline-mage-d6 hover:outline-1 active:outline-mage-d6',
    secondary: 'bg-charcoal-d20 hover:bg-charcoal-d12 outline outline-1 outline-charcoal-d10 active:outline-charcoal-d8',
    ghost: 'text-sand-d6 outline outline-1 outline-charcoal-d10 hover:bg-charcoal-d20/50'
  }
  
  const sizeClasses = {
    normal: 'px-6 py-4 text-base font-thin',
    xs: 'px-8 py-2 text-sm font-thin',
    icon: 'px-2 py-4 text-sm font-thin'
  }
  

  </script>