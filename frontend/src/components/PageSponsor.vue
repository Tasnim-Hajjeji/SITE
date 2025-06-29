<template>
  <div class="hero bg-base-200 min-h-screen">
    <div class="hero-content flex-col lg:flex-row">
      <div>
        <h1 class="text-5xl font-bold">{{ $t("sponsors.slang") }} –<br>{{ $t("sponsors.call_for") }}</h1>
        <p class="py-6">
          {{ $t("sponsors.promote") }}
        </p>
        <button
          class="download-btn animated-image capitalize shadow-lg transition-transform duration-200 hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-300"
          @click="openOrDownload(dossier_file)"
        >
          <i class="fas fa-download mr-2"></i> {{ $t("sponsors.download") }}
        </button>
      </div>
      <img
        src="@/assets/sponsoring.jpg"
        class="sponsor-image rounded-3xl shadow-2xl"
        alt="Sponsorship handshake"
      />
    </div>
    <div class="text-center px-4">
      <p class="text-2xl font-semibold animated-text text-gray-800 tracking-wide mx-8" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.1);">
        {{ $t("sponsors.thanks") }}
      </p>
    </div>
  </div>
</template>

<script setup>
import EditionService from '@/services/EditionService';
import { onMounted, ref } from 'vue';
const dossier_file = ref('');
onMounted(() => {
  EditionService.getCurrentEdition().then(response => {
    dossier_file.value = "http://localhost:8000/storage/" + response.data.dossier_sponso;
  }).catch(error => console.log(error));
});

const openOrDownload = (url) => {
  window.open(url, '_blank');
};
</script>

<style scoped>
.hero {
  font-family: 'Segoe UI', sans-serif;
  margin-bottom: -4rem;
}

.hero-content {
  padding: 2rem 1rem;
  display: flex;
  flex-direction: column;
  gap: 2rem; 
  margin-left: 10px;/* Space between content and image on mobile */
}

@media (min-width: 1024px) { /* lg breakpoint in DaisyUI */
  .hero-content {
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
  }
  .hero-content > div {
    flex: 1; /* Allow content to take available space */
  }
  .hero-content img {
    flex: 1;
    max-width: 50%; /* Ensure image doesn't overpower */
  }
}

.animated-image {
  animation: pulse 1s ease-in-out infinite alternate;
}

@keyframes pulse {
  0% {
    opacity: 0.8;
    transform: scale(1);
  }
  100% {
    opacity: 1;
    transform: scale(1.05);
  }
}

.text-center {
  padding: 2rem 1rem;
}

@media (max-width: 768px) {
  .hero-content img {
    display: none;
  }
  .hero-content {
    text-align: center;
  }
}

.sponsor-image {
  width: 400px;
  max-width: 100%;
  height: 400px;
  object-fit: cover;
  border-radius: 3rem;
  box-shadow: 0 8px 32px rgba(0,0,0,0.15);
}

.download-btn {
  background-color: #265985; /* Blue-700 from Tailwind */
  color: #ffffff; /* White text */
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 0.75rem; /* Elegant rounded corners */
  font-size: 1.1rem;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow */
  transition: all 0.2s ease-in-out;
}

.download-btn:hover {
  background-color: #172554; /* Darker blue on hover */
  transform: scale(1.05); /* Slight scale-up */
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15); /* Enhanced shadow */
}

.download-btn:focus {
  outline: none;
  ring: 4px; /* DaisyUI focus ring */
  ring-color: #3b82f6; /* Blue-500 for focus ring */
}

.download-btn i {
  font-size: 1.25rem; /* Larger icon for balance */
}

.animated-text {
  animation: fadeInUp 1.5s ease-out;
}

@keyframes fadeInUp {
  0% {
    opacity: 0;
    transform: translateY(20px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>