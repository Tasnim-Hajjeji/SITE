<template>
    <div v-if="editionData && editionData.id">
        <HeaderEdition :edition-data="editionData" />
        <PrevTopicsCarousel />
        <Keynote :edition-id="editionData.id" />
        <ConferenceGallery :edition-data="editionData" />
        <!-- <PrevTimeLine :edition-id="editionData.id" /> -->
        <GuestGrid :edition-id="editionData.id" />
        <Document :edition-id="editionData.id" />

    </div>
    <div v-else-if="editionData && !editionData.id">
        <h1 class="text-center text-2xl font-bold">No previous edition available</h1>
        <p class="text-center">Please check back later.</p>
    </div>
    <div v-else>
        <!-- Optionally, show a loading spinner here -->
        Loading...
    </div>
</template>

<script setup>
import HeaderEdition from '@/components/HeaderEdition.vue';
// import PrevTimeLine from '@/components/PrevTimeLine.vue';
import PrevTopicsCarousel from '@/components/PrevTopicsCarousel.vue';
import Keynote from '@/components/Keynote.vue';
import Document from '@/components/Document.vue';
import ConferenceGallery from '@/components/ConferenceGallery.vue';
import GuestGrid from '@/components/GuestGrid.vue';
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import EditionService from '@/services/EditionService.js';

const editionData = ref({});
const route = useRoute();
onMounted(() => {
    try {
        let id = route.query.id;
        EditionService.getEdition(id).then(response => {
            editionData.value = response.data;
        }).catch(error => {
            console.error('Error fetching edition data:', error);
        });
    } catch (error) {
        console.error('Error fetching previous edition data:', error);
    }
});
</script>