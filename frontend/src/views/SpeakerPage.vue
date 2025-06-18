<template>
  <VoicesHeader />
  <SpeakerCard
      name="Dr. Amina Ben Salah"
      institution="Université de Tunis"
      intervention="L'intelligence artificielle dans l'éducation moderne"
      image="https://randomuser.me/api/portraits/women/44.jpg"
    />
    <SpeakerCard
      name="Prof. John Smith"
      institution="MIT"
      intervention="Quantum Computing: The Next Frontier"
      image="https://randomuser.me/api/portraits/men/32.jpg"
    />
    <SpeakerCard
      name="Sara Chen"
      institution="Peking University"
      intervention="Sustainable Urban Development"
      image="https://randomuser.me/api/portraits/women/68.jpg"
    />    
</template>
      
<script setup>
  import VoicesHeader from '@/components/VoicesHeader.vue';
  import SpeakerCard from '@/components/SpeakerCard.vue';
</script>
      