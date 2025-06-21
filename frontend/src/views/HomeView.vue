<template>
  <HeroSection :edition-data="editionData" />
  <BlueSection :edition-data="editionData" />
  <TopicsCarousel :edition-id="editionData?.id" />
  <PartnersCarousel :edition-id="editionData?.id" />
  <SponsorsCarousel :edition-id="editionData?.id" />
  <div id="date">
    <ImportantDates :edition-id="editionData?.id" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import HeroSection from '@/components/HeroSection.vue';
import BlueSection from '@/components/BlueSection.vue';
import TopicsCarousel from '@/components/TopicsCarousel.vue';
import PartnersCarousel from '@/components/PartnersCarousel.vue';
import ImportantDates from '@/components/ImportantDates.vue';
import SponsorsCarousel from '@/components/SponsorsCarousel.vue';
import EditionService from '@/services/EditionService';

const editionData = ref({});

onMounted(async () => {
  try {
    const response = await EditionService.getCurrentEdition();
    editionData.value = response.data;
  } catch (error) {
    console.error('Error fetching edition data:', error);
  }
});
</script>