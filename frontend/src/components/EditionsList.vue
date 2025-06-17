<template>
    <section class="editions-section">
      <div class="header">
        <h2>Editions</h2>
        <button class="new-edition-btn">
          <span>➕</span> New Edition
        </button>
      </div>
  
      <div class="edition-card" v-for="edition in editions" :key="edition.year">
        <img class="edition-image" :src="edition.image" alt="Edition image" />
  
        <div class="edition-info">
          <div class="title-status">
            <h3>Site {{ edition.year }}</h3>
            <span class="status">En Cours</span>
          </div>
  
          <div class="details">
            <div class="detail">
              <i class="icon">📍</i>
              <div>
                <strong>Place</strong>
                <p>Hammamet</p>
              </div>
            </div>
            <div class="detail">
              <i class="icon">🕒</i>
              <div>
                <strong>Date</strong>
                <p>24 October</p>
              </div>
            </div>
          </div>
        </div>
  
        <button class="voir-plus">Voir plus</button>
      </div>
    </section>
  </template>
  
  <script setup>
  const editions = [
    {
      year: 2025,
      image: require('@/assets/logo.png'),
    },
    {
      year: 2024,
      image: require('@/assets/logo.png'),
    },
    {
      year: 2023,
      image: require('@/assets/logo.png'),
    },
    {
      year: 2022,
      image: require('@/assets/logo.png'),
    },
  ];
  </script>
  
  <style scoped>
  .editions-section {
    font-family: 'Segoe UI', sans-serif;
    width: 100%;
    max-width: 1200px;
    margin: auto;
    padding: 1rem;
  }
  
  .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
  }
  
  .header h2 {
    font-size: 1.7rem;
    color: #265985;
  }
  
  .new-edition-btn {
    border: 1px solid #000;
    background: white;
    padding: 0.5rem 1rem;
    border-radius: 999px;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
  }
  
  .new-edition-btn:hover {
    background-color: #eae9e9;
    transform: scale(1.05);
  }
  
  .edition-card {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
    background: white;
    border-radius: 15px;
    margin-top: 1.5rem;
    padding: 1rem;
    box-shadow: 0 9px 40px rgba(0, 0, 0, 0.05);
    gap: 1rem;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    animation: fadeInUp 0.6s ease;
  }
  
  .edition-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 50px rgba(0, 0, 0, 0.1);
  }
  
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  .edition-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 10px;
    flex-shrink: 0;
    transition: transform 0.3s ease;
  }
  
  .edition-card:hover .edition-image {
    transform: rotate(2deg) scale(1.05);
  }
  
  .edition-info {
    flex: 1;
    min-width: 220px;
  }
  
  .title-status {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.5rem;
  }
  
  .title-status h3 {
    color: #265985;
    font-size: 1.2rem;
  }
  
  .status {
    border: 1px solid #265985;
    border-radius: 999px;
    padding: 0.2rem 0.8rem;
    font-size: 0.8rem;
    color: #265985;
  }
  
  .details {
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
  }
  
  .detail {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #666;
  }
  
  .detail strong {
    color: #3c3c9c;
    font-size: 0.9rem;
  }
  
  .icon {
    font-size: 1.3rem;
    color: #6a0dad;
  }
  
  .voir-plus {
    background: #265985;
    color: white;
    border: none;
    border-radius: 10px;
    padding: 0.7rem 1.2rem;
    font-weight: bold;
    cursor: pointer;
    margin-left: auto;
    margin-top: 1rem;
    transition: background 0.3s ease, transform 0.3s ease;
  }
  
  .voir-plus:hover {
    background: #1e4b6b;
    transform: translateY(-2px);
  }
  
  /* RESPONSIVE */
  @media (max-width: 768px) {
    .edition-card {
      flex-direction: column;
      align-items: flex-start;
    }
  
    .voir-plus {
      width: 100%;
    }
  
    .details {
      flex-direction: column;
      gap: 1rem;
    }
  
    .header h2 {
      font-size: 1.5rem;
    }
  
    .new-edition-btn {
      width: 100%;
      justify-content: center;
    }
  }
  </style>
  