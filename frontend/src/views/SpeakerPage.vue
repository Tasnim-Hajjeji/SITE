<template>
  <VoicesHeader />
  <SpeakerCard v-for="speaker in speakers" :key="speaker.id" :speaker="speaker" />
</template>
      
<script setup>
  import VoicesHeader from '@/components/VoicesHeader.vue';
  import SpeakerCard from '@/components/SpeakerCard.vue';
  import cookieUtils from '@/utils/cookieUtils';
  import IntervenantService from '@/services/IntervenantService';

  import { onMounted, ref } from 'vue';
  const speakers = ref([]);
  onMounted(async () => {
    try {
      const editionId = cookieUtils.getCookie('editionId');
      const response = await IntervenantService.getIntervenantsByEdition(editionId);
      speakers.value = response.data;
    } catch (error) {
      console.error('Error fetching speakers:', error);
    }
  });
</script>
      