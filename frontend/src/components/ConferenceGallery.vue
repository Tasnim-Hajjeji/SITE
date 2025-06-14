<template>
    <div class="gallery-container">
      <h2 class="gallery-title">SITE 2024 Conference Gallery</h2>
      <p class="gallery-subtitle">
        Relive the highlights of previous editions through our photo gallery.<br />
        Discover the atmosphere, the speakers, and the discussions that marked each conference.
      </p>
  
      <div class="carousel">
        <button class="arrow left" @click="prevSlide">←</button>
        
        <div class="carousel-track">
          <div
            class="card"
            v-for="(item, index) in visibleSlides"
            :key="index"
          >
            <div class="image-container">
              <img :src="item.image" alt="Slide" />
              <div v-if="item.caption" class="caption-overlay">
                {{ item.caption }}
              </div>
            </div>
          </div>
        </div>
  
        <button class="arrow right" @click="nextSlide">→</button>
      </div>
  
      <div class="dots">
        <span
          v-for="(dot, i) in totalPages"
          :key="i"
          :class="['dot', { active: i === currentPage }]"
          @click="goToSlide(i)"
        ></span>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue'
  
  const slides = [
    { image: '../assets/i3.jpeg' },
    { image: '../assets/i1.jpeg'},
    { image: '../assets/i2.jpeg'},
    { image: '../assets/i1.jpeg' },
    { image: '../assets/i3.jpeg'},
    
    { image: '../assets/i3.jpeg'},
  ]
  
  const currentPage = ref(0)
  
  const cardsPerPage = computed(() => {
    if (window.innerWidth >= 1024) return 3
    if (window.innerWidth >= 640) return 2
    return 1
  })
  
  const visibleSlides = computed(() => {
    const start = currentPage.value * cardsPerPage.value
    return slides.slice(start, start + cardsPerPage.value)
  })
  
  const totalPages = computed(() =>
    Math.ceil(slides.length / cardsPerPage.value)
  )
  
  function nextSlide() {
    currentPage.value = (currentPage.value + 1) % totalPages.value
  }
  
  function prevSlide() {
    currentPage.value =
      (currentPage.value - 1 + totalPages.value) % totalPages.value
  }
  
  function goToSlide(index) {
    currentPage.value = index
  }
  
  window.addEventListener('resize', () => {
    currentPage.value = 0
  })
  </script>
  
  <style scoped>
  .gallery-container {
    padding: 40px 20px;
    max-width: 1200px;
    margin: auto;
    font-family: Arial, sans-serif;
  }
  
  .gallery-title {
    font-size: 28px;
    font-weight: bold;
    margin-bottom: 10px;
  }
  
  .gallery-subtitle {
    color: #2a4b94;
    margin-bottom: 30px;
    line-height: 1.5;
  }
  
  .carousel {
    position: relative;
    display: flex;
    align-items: center;
    overflow: hidden;
  }
  
  .carousel-track {
    display: flex;
    gap: 20px;
    flex: 1;
    transition: all 0.3s ease;
    justify-content: center;
  }
  
  .card {
    flex: 0 0 auto;
    width: 100%;
    max-width: 300px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
    overflow: hidden;
    background: white;
  }
  
  .image-container {
    position: relative;
    width: 100%;
    height: 100%;
  }
  
  .image-container img {
    width: 100%;
    height: 280px;
    object-fit: cover;
  }
  
  .caption-overlay {
    position: absolute;
    bottom: 0;
    width: 100%;
    background: rgba(0, 0, 0, 0.6);
    color: white;
    padding: 8px 12px;
    font-size: 14px;
    text-align: center;
  }
  
  .arrow {
    background: white;
    border: 2px solid #2a4b94;
    color: #2a4b94;
    border-radius: 50%;
    font-size: 18px;
    padding: 6px 10px;
    cursor: pointer;
    z-index: 10;
  }
  
  .arrow.left {
    margin-right: 10px;
  }
  .arrow.left:hover {
    background-color: #2a4b94;
    color: white;
  }
  
  .arrow.right:hover {
    background-color: #2a4b94;
    color: white;
  }
  .arrow.right {
    margin-left: 10px;
  }
  
  .dots {
    text-align: center;
    margin-top: 20px;
  }
  
  .dot {
    display: inline-block;
    height: 10px;
    width: 10px;
    background-color: #ccc;
    border-radius: 50%;
    margin: 0 5px;
    cursor: pointer;
  }
  
  .dot.active {
    background-color: #2a4b94;
  }
  </style>
  