<template>
    <PageSponsor />
    <SponsorsCarousel v-if="editionId" :edition-id="editionId" />
    <SponsorForm />
</template>
    
<script setup>
    import PageSponsor from '@/components/PageSponsor.vue';
    import SponsorsCarousel from '@/components/SponsorsCarousel.vue';
    import SponsorForm from '@/components/SponsorForm.vue';
    import cookieUtils from '@/utils/cookieUtils';
    import { onMounted,ref } from 'vue';
    var editionId = ref(null);
    onMounted(() => {
        editionId.value = cookieUtils.getCookie("editionId");
        if (!editionId.value) {
            console.error("Edition ID not found in cookies.");
        }
    });
    </script>