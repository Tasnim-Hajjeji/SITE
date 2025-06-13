<template>

  <SiteNavbar />
  <router-view />
  <ContactUs />

</template>

<script setup>
import SiteNavbar from '@/components/SiteNavbar.vue';
import ContactUs from '@/components/ContactUs.vue';
</script>