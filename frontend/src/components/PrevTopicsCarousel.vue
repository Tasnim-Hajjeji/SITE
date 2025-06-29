<template>
  <div class="carousel-wrapper">
    <div class="section-title">
      <h2 class="carousel-title">{{ $t("previousEditions.topics") }}</h2>
    </div>

    <button @click="prev" class="nav-button left" :disabled="currentIndex === 0">
      <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
        <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
      </svg>
    </button>

    <div class="carousel-container">
      <div 
        class="carousel-track" 
        :style="{ transform: `translateX(-${currentIndex * (100 / visibleItems)}%)` }"
      >
        <div class="carousel-item" v-for="(topic, index) in topics" :key="index">
          <div class="card">
            <img :src="topic.image" :alt="topic.title_en" />
            <h3 class="card-title">{{ translate(topic.title_fr,topic.title_en) }}</h3>
            <p>{{ translate(topic.description_fr,topic.description_en) }}</p>
          </div>
        </div>
      </div>
    </div>

    <button 
      @click="next" 
      class="nav-button right" 
      :disabled="currentIndex >= topics.length - visibleItems"
    >
      <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
        <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/>
      </svg>
    </button>
    <div class="bottom-line-wrapper">
      <hr class="custom-line" />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useI18n } from 'vue-i18n'
const { locale } = useI18n()

const currentIndex = ref(0)
const visibleItems = ref(4)

const topics = [
  {
    title_en: 'Engineering Innovation',
    title_fr: "Innovation en ingénierie",
    description_en: 'Smart factory, robotics, Cybersecurity, AI, materials, Energy management, Cloud Computing, Big Data, Machine Learning !',
    description_fr: 'Usine intelligente, robotique, cybersécurité, IA, matériaux, gestion de l\'énergie, Cloud Computing, Big Data, Machine Learning !',
    image: require('@/assets/topic1.png')
  },
  {
    title_en: 'Pedagogy, Teaching and Socio-human processes',
    title_fr: "Pédagogie, Enseignement et Processus Socio-Humains",
    description_en: 'Pedagogy, Serious Game, Case of Study !',
    description_fr: 'Pédagogie, Serious Game, Cas d\'Etude !',
    image: require('@/assets/topic2.png')
  },
  {
    title_en: 'New Technologies',
    title_fr: "Nouvelles Technologies",
    description_en: 'AR/VR, IoT, Quantum Computing, Edge Computing, Web3 !',
    description_fr: 'AR/VR, IoT, Informatique Quantique, Edge Computing, Web3 !',
    image: require('@/assets/topic3.png')
  },
  {
    title_en: 'Social Impact',
    title_fr: "Impact Social",
    description_en: 'Tech for good, Education, HealthTech, CivicTech !',
    description_fr: 'Tech pour le bien, Education, HealthTech, CivicTech !',
    image: require('@/assets/topic4.png')
  }
]

function updateVisibleItems() {
  const width = window.innerWidth
  if (width >= 1024) visibleItems.value = 4
  else if (width >= 600) visibleItems.value = 2
  else visibleItems.value = 1
  
  if (currentIndex.value > topics.length - visibleItems.value) {
    currentIndex.value = Math.max(0, topics.length - visibleItems.value)
  }
}

function translate(fr,en){
  return locale.value === 'fr' ? fr : en;
}

onMounted(() => {
  updateVisibleItems()
  window.addEventListener('resize', updateVisibleItems)
})

onUnmounted(() => {
  window.removeEventListener('resize', updateVisibleItems)
})

function next() {
  if (currentIndex.value < topics.length - visibleItems.value) {
    currentIndex.value++
  }
}

function prev() {
  if (currentIndex.value > 0) {
    currentIndex.value--
  }
}
</script>

<style scoped>
.section-title {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 2rem;
  position: relative;
  text-align: center;
}

.section-title::before,
.section-title::after {
  content: "";
  flex: 1;
  height: 1px;
  background-color: #265985;
  margin: 0 1rem;
}

.section-title h2 {
  font-size: 1.2rem;
  color: #265985;
  font-weight: bold;
  border: 1px solid #265985;
  border-radius: 999px;
  padding: 0.5rem 2rem;
  background-color: #fff;
  white-space: nowrap;
}

.carousel-wrapper {
  width: 100%;
  overflow: hidden;
  position: relative;
  font-family: 'Segoe UI', sans-serif;
  background-color: #ffffff;
  margin-top: 3rem;
}

.carousel-title {
  text-align: center;
  font-size: 1.3rem;
  font-weight: bold;
  margin-bottom: 2rem;
  color: #265985;
}

.carousel-container {
  overflow: hidden;
  max-width: 1000px;
  margin: 0 auto;
}

.carousel-track {
  display: flex;
  transition: transform 0.5s ease-in-out;
  width: 100%;
}

.carousel-item {
  margin-top: -2rem;
  flex: 0 0 calc(100% / 4); /* Par défaut 4 items */
  padding: 1rem;
  box-sizing: border-box;
}

.bottom-line-wrapper {
  display: flex;
  justify-content: center;
  margin-top: 2rem;
}

.custom-line {
  width: 96%;
  height: 1px;
  background-color: #265985;
  border: none;
  position: relative;
}

.custom-line::before,
.custom-line::after {
  content: '';
  position: absolute;
  height: 1px;
  background-color: #265985;
  width: 50px;
  top: 0;
}

.custom-line::before {
  left: -60px;
}

.custom-line::after {
  right: -60px;
}

/* Carte uniforme */
.card {
  background-color: white;
  border-radius: 1rem;
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
  height: 350px; /* Hauteur fixe */
  width: 100%; /* Largeur fixe relative au conteneur */
  display: flex;
  flex-direction: column;
  justify-content: space-between; /* Répartit l'espace entre image, titre et texte */
  padding: 1rem;
  box-sizing: border-box;
}

.card img {
  border-radius: 0.75rem;
  width: 100%;
  height: 150px; /* Hauteur fixe pour l'image */
  object-fit: cover;
  margin-bottom: 0.5rem;
}

.card-title {
  font-size: 1rem;
  font-weight: 600;
  color: #1f2937;
  margin: 0.5rem 0;
  text-align: center; /* Titre centré à un emplacement spécifique */
}

.card p {
  font-size: 0.8rem;
  color: #4b5563;
  margin: 0;
  flex-grow: 1; /* Permet au texte de prendre l'espace restant */
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 3; /* Limite à 3 lignes */
  -webkit-box-orient: vertical;
}

.nav-button {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background-color: white;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
  padding: 0.5rem;
  border-radius: 9999px;
  z-index: 10;
  cursor: pointer;
  border: none;
  transition: opacity 0.3s ease;
}

.nav-button:disabled {
  cursor: not-allowed;
  opacity: 0.3;
}

.nav-button.left {
  left: 0.5rem;
}

.nav-button.right {
  right: 0.5rem;
}

.nav-button svg {
  width: 24px;
  height: 24px;
  color: #4b5563;
}

/* Responsive */
@media (max-width: 1023px) {
  .carousel-item {
    flex: 0 0 calc(100% / 2);
  }
}

@media (max-width: 599px) {
  .carousel-item {
    flex: 0 0 100%;
  }
  .card {
    height: 250px; /* Ajustement pour petits écrans */
  }
  .card img {
    height: 120px; /* Ajustement pour petits écrans */
  }
}
</style>