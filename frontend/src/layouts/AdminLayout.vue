<template>
  <NavbarAdmin />
  <div class="admin-layout">
    <button class="burger" @click="toggleSidebar">☰</button>
    <NavbarLeft ref="sidebarRef" />
    <div :class="['main-content', { shifted: isSidebarOpen }]">
      
      <router-view />

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import NavbarAdmin from '@/components/Navbar.vue'
import NavbarLeft from '@/components/NavbarLeft.vue'

const sidebarRef = ref(null)
const isSidebarOpen = ref(true)

const toggleSidebar = () => {
  sidebarRef.value.toggleSidebar()
  isSidebarOpen.value = !isSidebarOpen.value
}

const handleResize = () => {
  isSidebarOpen.value = window.innerWidth >= 768
}

onMounted(() => {
  handleResize()
  window.addEventListener('resize', handleResize)
})

onUnmounted(() => {
  window.removeEventListener('resize', handleResize)
})
</script>

<style scoped>
.admin-layout {
  display: flex;
}

.burger {
  position: fixed;
  top: 0rem;
  left: 0rem;
  background: white;
  color:  #265985;
  border: none;
  font-size: 1.5rem;
  z-index: 1000;
  display: block;
  padding: 0.4rem 1rem;
  border-radius: 5px;
  cursor: pointer;
}

/* cacher burger sur desktop */
@media (min-width: 768px) {
  .burger {
    display: none;
  }
  
}

.main-content {
  padding: 2rem;
  width: 100%;
  transition: margin-left 0.3s ease;
}

/* sur desktop, décale le contenu */
@media (min-width: 768px) {
  .main-content.shifted {
    margin-left: 220px;
  }
}
</style>
