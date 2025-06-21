<template>
    <section class="sponsor-section">
      <div class="sponsor-container">
        <!-- LEFT PART -->
        <div class="sponsor-text">
          <h1 class="title">Empower Innovation –<br>Become a SITE 2025 Sponsor</h1>
          <p class="subtitle">
            Promote your brand at the heart of tech and sustainability !
          </p>
          <button class="download-btn">
            <span class="icon">⬇️</span> Download sponsor file
          </button>
        </div>
  
        <!-- RIGHT PART (Image) -->
        <div class="sponsor-image">
          <img src="@/assets/i1.jpeg" alt="Sponsorship handshake" />
        </div>
      </div>
  
      <!-- FOOTER TEXT -->
      <div class="sponsor-footer">
        <p>
          We sincerely thank all our previous sponsors for their valuable support
          and contribution to the success of past SITE editions.
        </p>
      </div>
    </section>
  </template>
  
  <script setup>
  // You can also import the image directly like:
  // import sponsorImage from '@/assets/sponsor-image.png'
  </script>
  
  <style scoped>
  .sponsor-section {
    background-color: #fefefe;
    font-family: 'Segoe UI', sans-serif;
  }
  
  .sponsor-container {
    display: flex;
    flex-wrap: wrap;
    background-color: #1b5a74;
    color: white;
    height: 30rem;
  }
  
  .sponsor-text {
    flex: 1 1 400px;
    padding: 3rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }
  
  .title {
    font-size: 1.7rem;
    font-weight: bold;
    margin-bottom: 1rem;
    line-height: 1.4;
  }
  
  .subtitle {
    font-size: 1.2rem;
    margin-bottom: 2rem;
  }
  
  .download-btn {
    background-color: #4fc1c6;
    color: white;
    padding: 0.8rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: background-color 0.3s;
  }
  
  .download-btn:hover {
    background-color: #3aa5aa;
  }
  
  .sponsor-image {
    flex: 1 1 400px;
    overflow: hidden;
    max-height: 100%;
  }
  
  .sponsor-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  
  .sponsor-footer {
    text-align: center;
    padding: 2rem 1rem;
    font-size: 1.1rem;
    color: #333;
  }
  @media (max-width: 768px) {
  .sponsor-image {
    display: none;
  }
}

  </style>
  