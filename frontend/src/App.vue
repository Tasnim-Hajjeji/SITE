<template>

  <SiteNavbar />
  <router-view />
  <div id="contact">
    <ContactUs />
  </div>

</template>

<script setup>
import SiteNavbar from '@/components/SiteNavbar.vue';
import ContactUs from '@/components/ContactUs.vue';
</script>