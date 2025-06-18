<template>
  <div v-if="!selectedEditionId">
    <EditionsList />
  </div>
  <div v-else>
    <AdEdition />
    </div>

</template>

<script setup>
import EditionsList from '@/components/EditionsList.vue';
import AdEdition from  '@/views/AdEdition.vue';
import { ref, onMounted } from 'vue'
const selectedEditionId = ref(null)

const checkSelectedEdition = () => {
  selectedEditionId.value = localStorage.getItem('selectedEditionId')
}
onMounted(() => {
  checkSelectedEdition()
  
  // Listen for storage changes (in case edition is selected from another tab)
  window.addEventListener('storage', checkSelectedEdition)
})
</script>