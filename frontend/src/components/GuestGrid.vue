<template>
    <section class="guest-section">
      <h2 class="title">Our guests of honor</h2>
      <p class="description">
        Discover the speakers for this edition: experts, professionals and inspiring personalities who will come to share their experiences and visions.
      </p>
      <div class="guest-grid">
        <div v-for="(guest, index) in guests" :key="index" class="guest-card">
          <img :src="guest.image" alt="Guest photo" class="guest-image" />
          <h3 class="guest-name">{{ guest.name }}</h3>
          <p class="guest-title">{{ guest.title }}</p>
          <img v-if="guest.flag" :src="guest.flag" alt="Flag" class="guest-flag" />
        </div>
      </div>
    </section>
  </template>
  
  <script setup>
  const guests = [
    {
      name: "LINDSAY LABAHN",
      title: "iset bz",
      image: "/images/guest1.jpg",
      flag: "https://flagcdn.com/tn.svg"
    },
    {
      name: "LINDSAY LABAHN",
      title: "ensi",
      image: "/images/guest2.jpg"
    },
    {
      name: "LINDSAY LABAHN",
      title: "enib",
      image: "/images/guest3.jpg",
      flag: "https://flagcdn.com/tn.svg"
    },
    {
      name: "LINDSAY LABAHN",
      title: "adt",
      image: "/images/guest4.jpg",
      flag: "https://flagcdn.com/dz.svg"
    },
    {
      name: "LINDSAY LABAHN",
      title: "Design Engineer",
      image: "/images/guest5.jpg",
      flag: "https://flagcdn.com/tn.svg"
    },
    {
      name: "LINDSAY LABAHN",
      title: "Design Engineer",
      image: "/images/guest6.jpg",
      flag: "https://flagcdn.com/tn.svg"
    },
    {
      name: "LINDSAY LABAHN",
      title: "Design Engineer",
      image: "/images/guest7.jpg",
      flag: "https://flagcdn.com/tn.svg"
    },
    {
      name: "LINDSAY LABAHN",
      title: "Design Engineer",
      image: "/images/guest8.jpg",
      flag: "https://flagcdn.com/tn.svg"
    },
    {
      name: "LINDSAY LABAHN",
      title: "Design Engineer",
      image: "/images/guest9.jpg",
      flag: "https://flagcdn.com/tn.svg"
    }
  ];
  </script>
  
  <style scoped>
  .guest-section {
    text-align: center;
    padding: 2rem 1rem;
    font-family: 'Segoe UI', sans-serif;
  }
  
  .title {
    font-size: 1.7rem;
    color: #1c3d6e;
    margin-bottom: 0.5rem;
    font-weight: bold;
  }
  
  .description {
    max-width: 600px;
    margin: 0 auto 2rem auto;
    font-size: 0.95rem;
    color: #5a5a5a;
  }
  
  .guest-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr); /* 5 colonnes par défaut */
    gap: 1.5rem;
    justify-items: center;
  }
  
  @media (max-width: 1200px) {
    .guest-grid {
      grid-template-columns: repeat(4, 1fr);
    }
  }
  
  @media (max-width: 992px) {
    .guest-grid {
      grid-template-columns: repeat(3, 1fr);
    }
  }
  
  @media (max-width: 768px) {
    .guest-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }
  
  @media (max-width: 480px) {
    .guest-grid {
      grid-template-columns: 1fr;
    }
  }
  
  .guest-card {
    background: #f3f4f2;
    border-radius: 20px;
    padding: 1rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    width: 180px;
    text-align: center;
    position: relative;
  }
  
  .guest-image {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 0.5rem;
  }
  
  .guest-name {
    font-size: 0.95rem;
    font-weight: bold;
    margin: 0.3rem 0;
  }
  
  .guest-title {
    font-size: 0.85rem;
    color: #444;
    margin-bottom: 0.5rem;
  }
  
  .guest-flag {
    width: 20px;
    height: auto;
  }
  </style>
  