<template>
  <div>
    <bandeLandingPage />  
    <SiteNavbar />
    

    <slot />
    <div >
      <Footer />
    </div>
  </div>
</template>

<script setup>
import SiteNavbar from '@/components/SiteNavbar.vue'
import BandeLandingPage from '@/components/bandeLandingPage.vue'
import Footer from '@/components/Footer.vue'

import { onMounted, onUnmounted } from 'vue';
onMounted(() => {
  window.addEventListener('admin-login', handleAdminAccess);
});
onUnmounted(() => {
  window.removeEventListener('admin-login', handleAdminAccess);
});
function handleAdminAccess() {
  alert('Access granted! Redirecting to admin page...');
  setTimeout(() => {
    window.location.href = '/admin';
  }, 1000);
}
</script>
