<template>
  <div>
    <AdminNavbar />
    <slot/>
    <AdminFooter />
  </div>
</template>

<script setup>
import AdminNavbar from '@/components/SiteNavbar.vue'
import AdminFooter from '@/components/ContactUs.vue'
</script>
