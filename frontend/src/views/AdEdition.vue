<template>
    <!-- Show EditionsList if no edition is selected, otherwise show selected edition content -->
    <div v-if="!selectedEditionId">
        <EditionsList />
    </div>
    <div v-else>
        <div class="edition-header">
            <div class="title-with-back">
                <button class="back-arrow" @click="goBackToEditions" title="Return to editions">
                    ←
                </button>
                <h1>Edition {{ selectedEditionId }}</h1>
            </div>
        </div>

        <div class="edition-content">
            <AdEdition />
            <PrevProgram />
            <KeynoteCarousel />
            <PhotoGallery />
        </div>
    </div>

</template>

<script setup>
import AdEdition from '@/components/AdEdition.vue';
import PrevProgram from '@/components/PrevProgram.vue';
import KeynoteCarousel from '@/components/KeynoteCarousel.vue';
import PhotoGallery from '@/components/PhotoGallery.vue';
import { ref, onMounted } from 'vue'
import EditionsList from '@/components/EditionsList.vue'

const selectedEditionId = ref(null)

const checkSelectedEdition = () => {
  selectedEditionId.value = localStorage.getItem('selectedEditionId')
}

const goBackToEditions = () => {
  localStorage.removeItem('selectedEditionId')
  selectedEditionId.value = null
}

onMounted(() => {
  checkSelectedEdition()
  
  // Listen for storage changes (in case edition is selected from another tab)
  window.addEventListener('storage', checkSelectedEdition)
})
</script>
<style scoped>
.edition-header {
  padding: 1rem;
  border-bottom: 1px solid #e5e5e5;
  margin-bottom: 2rem;
}

.title-with-back {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.back-arrow {
  background: #265985;
  color: white;
  border: none;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-size: 18px;
  font-weight: bold;
  transition: all 0.3s ease;
}

.back-arrow:hover {
  background: #1e4b6b;
  transform: scale(1.1);
}

.title-with-back h1 {
  color: #265985;
  font-size: 1.8rem;
  margin: 0;
  font-family: 'Segoe UI', sans-serif;
}

.edition-content {
  padding: 1rem;
  font-family: 'Segoe UI', sans-serif;
}
</style>