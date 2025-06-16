<template>
  <div>
    <SiteNavbar />
    <slot />
    <div id="contact">
      <ContactUs />
    </div>
  </div>
</template>

<script setup>
import SiteNavbar from '@/components/SiteNavbar.vue'
import ContactUs from '@/components/ContactUs.vue'
</script>
