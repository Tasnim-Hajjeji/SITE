<template>
    <section class="carousel-container">
      <h2 class="carousel-title">
        Conference Highlights from <span class="highlight">Previous Years</span> !
      </h2>
      <p class="carousel-subtitle">
        From inspiring talks to vibrant exchanges, each edition brought something unique.
        <br />
        Relive the highlights through photos and memories.
      </p>
  
      <div class="carousel-wrapper">
        <div class="carousel-preview left-preview" :style="{ backgroundImage: `url(${getPrevSlide().image})` }"></div>
  
        <div class="carousel-main" :style="{ backgroundImage: `url(${slides[currentIndex].image})` }">
          <div class="carousel-overlay"></div>
          <div class="carousel-caption">
            <h3>{{ slides[currentIndex].title }}</h3>
            <p>{{ slides[currentIndex].description }}</p>
            <router-link to="/previous-editions/edition" class="carousel-button">
              {{ slides[currentIndex].buttonText }}
              <span>➜</span></router-link>
            

           </div>
        </div>
  
        <div class="carousel-preview right-preview" :style="{ backgroundImage: `url(${getNextSlide().image})` }"></div>
      </div>
  
      <div class="carousel-controls">
        <button @click="prevSlide">←</button>
        <button @click="nextSlide">→</button>
      </div>
    </section>
    <div class="custom-line"></div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  
  const slides = [
    {
      title: 'SITE 2024',
      description: 'The second edition of the international conference on Smart Industry, Technology and Environment',
      image: new URL('@/assets/i1.jpeg', import.meta.url).href,
      buttonText: 'See More',
    },
    {
      title: 'SITE 2023',
      description: 'First edition — vibrant moments and tech inspiration.',
      image: new URL('@/assets/i2.jpeg', import.meta.url).href,
      buttonText: 'Discover More',
    },
    {
      title: 'SITE 2022',
      description: 'Laying the foundations of an amazing journey.',
      image: new URL('@/assets/i3.jpeg', import.meta.url).href,
      buttonText: 'Explore',
    },
  ]
  
  const currentIndex = ref(0)
  
  function nextSlide() {
    currentIndex.value = (currentIndex.value + 1) % slides.length
  }
  
  function prevSlide() {
    currentIndex.value = (currentIndex.value - 1 + slides.length) % slides.length
  }
  
  function getPrevSlide() {
    const index = (currentIndex.value - 1 + slides.length) % slides.length
    return slides[index]
  }
  
  function getNextSlide() {
    const index = (currentIndex.value + 1) % slides.length
    return slides[index]
  }
  </script>
  
  <style scoped>
  .carousel-container {
    text-align: center;
    padding: 0.5rem;
    font-family: 'Segoe UI', sans-serif;

  }
  
  .carousel-title {
    font-size: 1.7rem;
  }
  
  .highlight {
    color: #245785;
  }
  
  .carousel-subtitle {
    font-size: 0.95rem;
    margin: 0.5rem 0 2rem;
    color: #444;
  }
  
  .carousel-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.3rem;
    width: 100%;
    max-width: 1000px;
    margin: auto;
  }
  
  .carousel-main {
    width: 60%;
    height: 400px;
    background-size: cover;
    background-position: center;
    border-radius: 1.5rem;
    position: relative;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    transition: background-image 0.5s ease-in-out;
    overflow: hidden;
  }
  
  /* 👇 L’overlay qui fait le flou */
  .carousel-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.4); /* fond sombre transparent */
    backdrop-filter: blur(0px);
    opacity: 0;
    transition: all 0.4s ease;
    border-radius: 1.5rem;
    z-index: 1;
  }
  
  .carousel-main:hover .carousel-overlay {
    opacity: 1;
    backdrop-filter: blur(3px);
  }
  
  /* 👇 Caption animation */
  .carousel-caption {
    position: absolute;
    bottom: 1.5rem;
    left: 2rem;
    color: white;
    text-align: left;
    max-width: 80%;
    z-index: 2;
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.4s ease;
  }
  
  .carousel-main:hover .carousel-caption {
    opacity: 1;
    transform: translateY(0);
  }
  
  .carousel-caption h3 {
    font-size: 1.4rem;
    margin-bottom: 0.5rem;
  }
  
  .carousel-caption p {
    font-size: 0.95rem;
    opacity: 0.9;
  }
  
  .carousel-button {
    margin-top: 1rem;
    background: transparent;
    color: white;
    border: 1px solid white;
    padding: 0.4rem 0.8rem;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s;
    text-decoration: none;
  }
  
  .carousel-button:hover {
    background: white;
    text-decoration: none;
    color: #333;
  }
  
  .carousel-preview {
    width: 20%;
    height: 300px;
    background-size: cover;
    background-position: center;
    border-radius: 1rem;
    filter: blur(3px) brightness(0.7);
    transition: transform 0.4s ease;
  }
  
  .left-preview:hover,
  .right-preview:hover {
    filter: blur(1.5px) brightness(0.9);
    transform: scale(1.02);
    cursor: pointer;
  }
  
  .carousel-controls {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 1.5rem;
  }
  
  .carousel-controls button {
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 50%;
    padding: 0.7rem 0.8rem;
    cursor: pointer;
    font-size: 1.2rem;
    transition: background 0.3s;
  }
  
  .carousel-controls button:hover {
    background: #245785;
    color: white;
  }
  
  .custom-line {
    height: 2px;
    width: 100%;
    background: linear-gradient(to right, rgba(0, 102, 204, 0), black, rgba(0, 102, 204, 0));
    border: none;
    margin: 20px 0;
  }
  
  @media (max-width: 768px) {
    .carousel-main {
      width: 80%;
      height: 250px;
    }
  
    .carousel-preview {
      display: none;
    }
  
    .carousel-caption h3 {
      font-size: 1.1rem;
    }
  
    .carousel-caption p {
      font-size: 0.8rem;
    }
  }
  </style>
  