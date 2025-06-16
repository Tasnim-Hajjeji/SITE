<template>
  <component :is="layoutComponent">
    <transition name="slide-up" mode="out-in">
      <router-view />
    </transition>
  </component>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'

// Layouts
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import AdminLayout from '@/layouts/AdminLayout.vue'

const route = useRoute()

const layoutComponent = computed(() => {
  return route.meta.layout === 'admin' ? AdminLayout : DefaultLayout
})
</script>

<style>
.slide-up-enter-active,
.slide-up-leave-active {
  transition: all 0.4s ease;
}
.slide-up-enter-from {
  opacity: 0;
  transform: translateY(30px);
}
.slide-up-enter-to {
  opacity: 1;
  transform: translateY(0);
}
.slide-up-leave-from {
  opacity: 1;
  transform: translateY(0);
}
.slide-up-leave-to {
  opacity: 0;
  transform: translateY(30px);
}
</style>
