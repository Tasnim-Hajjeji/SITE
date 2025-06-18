<template>
  <div class="gallery-container">
    <h2 class="gallery-title">SITE 2024 Conference Gallery</h2>
    <p class="gallery-subtitle">
      Relive the highlights of previous editions through our photo gallery.<br />
      Discover the atmosphere, the speakers, and the discussions that marked each conference.
    </p>

    <div class="carousel">
      <button class="arrow left" @click="prevSlide" :disabled="currentIndex === 0">←</button>
      
      <div class="carousel-track" :style="{ transform: `translateX(-${currentIndex * (100 / 3)}%)` }">
        <div
          class="card"
          v-for="(item, index) in slides"
          :key="index"
          :class="{ 'center-card': isCenterCard(index), 'partial-card': isPartialCard(index) }"
        >
          <div class="image-container">
            <img :src="item.image" :alt="item.caption" @mouseover="showCaption(index)" @mouseleave="hideCaption(index)" />
            <div v-if="item.showCaption" class="caption-overlay">
              <p class="caption-title">{{ item.caption }}</p>
              <p class="caption-desc">{{ item.description }}</p>
            </div>
          </div>
        </div>
      </div>

      <button class="arrow right" @click="nextSlide" :disabled="currentIndex >= slides.length - 4">→</button> <!-- Ajusté pour -4 -->
    </div>

    <div class="dots">
      <span
        v-for="(dot, i) in totalPages"
        :key="i"
        :class="['dot', { active: Math.floor(currentIndex / 3) === i }]"
        @click="goToSlide(i)"
      ></span>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

// eslint-disable-next-line no-unused-vars
const cardsPerPage = ref(3) // Suppressed warning, kept for potential future use
// eslint-disable-next-line no-unused-vars
const visibleSlides = computed(() => slides) // Suppressed warning, kept for potential future use

const slides = [
  { image: require('@/assets/souvenirPrevious1.png'), caption: 'Souvenir 1', description: 'Memories from 2023 opening ceremony.' },
  { image: require('@/assets/souvenirPrevious2.png'), caption: 'Souvenir 2', description: 'Keynote speech highlights from 2022.' },
  { image: require('@/assets/souvenirPrevious3.png'), caption: 'Souvenir 3', description: 'Networking event from 2021.' },
  { image: require('@/assets/souvenirPrevious1.png'), caption: 'Souvenir 1', description: 'Memories from 2023 opening ceremony.' },
  { image: require('@/assets/souvenirPrevious2.png'), caption: 'Souvenir 2', description: 'Keynote speech highlights from 2022.' },
  { image: require('@/assets/souvenirPrevious3.png'), caption: 'Souvenir 3', description: 'Networking event from 2021.' },
  { image: require('@/assets/souvenirPrevious1.png'), caption: 'Souvenir 1', description: 'Memories from 2023 opening ceremony.' },
  { image: require('@/assets/souvenirPrevious2.png'), caption: 'Souvenir 2', description: 'Keynote speech highlights from 2022.' },
  { image: require('@/assets/souvenirPrevious3.png'), caption: 'Souvenir 3', description: 'Networking event from 2021.' },
]

const currentIndex = ref(0)

const totalPages = computed(() =>
  Math.ceil(slides.length / 3)
)

function nextSlide() {
  if (currentIndex.value < slides.length - 4) { // Ajusté pour inclure la 4ème image
    currentIndex.value += 1 // Défilement d'une image à la fois
  }
}

function prevSlide() {
  if (currentIndex.value > 0) {
    currentIndex.value -= 1 // Défilement d'une image à la fois
  }
}

function goToSlide(page) {
  currentIndex.value = page * 3 // Aller à la page correspondante (début du groupe de 3)
}

function showCaption(index) {
  slides[index].showCaption = true
}

function hideCaption(index) {
  slides[index].showCaption = false
}

function isCenterCard(index) {
  const centerIndex = currentIndex.value + 1 // L'index central dans le groupe visible
  return index === centerIndex
}

function isPartialCard(index) {
  const lastIndex = currentIndex.value + 3 // Dernier index du groupe visible
  return index === lastIndex && index < slides.length
}

window.addEventListener('resize', () => {
  currentIndex.value = 0
})
</script>

<style scoped>
.gallery-container {
  padding: 40px 20px;
  max-width: 1200px;
  margin: 0 auto;
  font-family: 'Arial', sans-serif;
  text-align: center;
}

.gallery-title {
  font-size: 28px;
  font-weight: 700;
  color: #2a4b94;
  margin-bottom: 10px;
}

.gallery-subtitle {
  color: #4b5563;
  margin-bottom: 30px;
  line-height: 1.6;
}

.carousel {
  position: relative;
  display: flex;
  align-items: center;
  overflow: hidden;
  margin: 0 auto;
  max-width: 1000px; /* Limite la largeur totale */
}

.carousel-track {
  display: flex;
  gap: 20px;
  transition: transform 0.6s ease, rotate 0.6s ease;
  transform-style: preserve-3d;
  width: calc(100% * 3 / 3); /* Ajusté pour 3 images principales */
}

.card {
  flex: 0 0 auto;
  width: 100%;
  max-width: 300px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  overflow: hidden;
  background: #fff;
  transition: transform 0.4s ease;
}

.center-card {
  max-width: 400px;
  max-height: 500px; /* Agrandissement en hauteur */
  transform: rotateY(0deg);
  z-index: 1;
}

.partial-card {
  max-width: 100px; /* 4ème image partiellement visible */
  max-height: 150px;
  opacity: 0.6;
  transform: translateX(-20%) rotateY(15deg); /* Ajusté pour apparaître légèrement à droite */
  position: relative;
  right: -50px; /* Décalage pour la rendre partielle */
}

.image-container {
  position: relative;
  width: 100%;
  height: 100%;
}

.image-container img {
  width: 100%;
  height: 400px;
  object-fit: cover;
  transition: filter 0.3s ease;
}

.image-container:hover img {
  filter: brightness(50%);
}

.caption-overlay {
  position: absolute;
  bottom: 0;
  width: 100%;
  background: rgba(0, 0, 0, 0.6);
  color: #fff;
  padding: 8px;
  font-size: 14px;
  text-align: center;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.caption-title {
  font-weight: 600;
  margin-bottom: 4px;
}

.caption-desc {
  font-size: 12px;
  margin: 0;
}

.image-container:hover .caption-overlay {
  opacity: 1;
}

.arrow {
  background: #fff;
  border: 2px solid #2a4b94;
  color: #2a4b94;
  border-radius: 50%;
  font-size: 18px;
  padding: 10px;
  cursor: pointer;
  z-index: 10;
  transition: background-color 0.3s ease, color 0.3s ease;
}

.arrow:hover {
  background-color: #2a4b94;
  color: #fff;
}

.arrow.left {
  margin-right: 10px;
}

.arrow.right {
  margin-left: 10px;
}

.dots {
  margin-top: 20px;
}

.dot {
  display: inline-block;
  width: 10px;
  height: 10px;
  background-color: #ccc;
  border-radius: 50%;
  margin: 0 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.dot.active {
  background-color: #2a4b94;
}

.dot:hover {
  background-color: #1e3a8a;
}
</style>