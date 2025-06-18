<template>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <div class="registration-container">
      <h2><span>Select your profile</span> to proceed with <span class="highlight">registration</span></h2>
      <p class="subtitle">Register now and join SITE 2025 according to your participant category</p>
  
      <div class="cards-container">
        <router-link to="/tunisian-form" class="profile-card tunisian">
          <h3>Tunisian</h3>
          <p class="price">650 TND</p>
          <ul>
            <li><i class="fas fa-bed"></i> Hébergement 2 nuits ALL IN SOFT</li>
            <li><i class="fas fa-mug-hot"></i> Coffee breaks</li>
            <li><i class="fas fa-users"></i> Access to plenary sessions</li>
            <li><i class="fas fa-chalkboard-teacher"></i> Training workshops</li>
          </ul>
        </router-link>
  
        <router-link to="/stranger-form" class="profile-card stranger">
          <h3>Stranger</h3>
          <p class="price">400 £</p>
          <ul>
            <li><i class="fas fa-bed"></i> Hébergement 2 nuits ALL IN SOFT</li>
            <li><i class="fas fa-mug-hot"></i> Coffee breaks</li>
            <li><i class="fas fa-users"></i> Access to plenary sessions</li>
            <li><i class="fas fa-chalkboard-teacher"></i> Training workshops</li>
          </ul>
        </router-link>
      </div><br>
    </div>
  </template>
  
  <script setup>
  // rien à déclarer ici pour l'instant
  </script>
  
  <style scoped>
  .registration-container {
    text-align: center;
    margin: 40px auto;
    max-width: 900px;
    font-family: 'Segoe UI', sans-serif;
  }
  
  .highlight {
    color: #3d5a80;
    font-weight: bold;
  }
  
  .subtitle {
    margin-top: 0.5rem;
    color: #333;
    font-size: 1rem;
  }
  
  .cards-container {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 2rem;
    margin-top: 2rem;
  }
  
  .profile-card {
    width: 280px;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    text-align: left;
    text-decoration: none;
    transition: transform 0.3s, box-shadow 0.3s;
  }
  
  .profile-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
  }
  
  .profile-card h3 {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
  }
  
  .profile-card .price {
    font-size: 1.8rem;
    font-weight: bold;
    margin-bottom: 1rem;
  }
  
  .profile-card ul {
    list-style: none;
    padding: 0;
    color: #555;
  }
  
  .profile-card ul li {
    margin: 0.6rem 0;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
  }
  
  .profile-card ul li i {
    margin-right: 0.6rem;
    color: #666;
  }
  
  /* Tunisian Card */
  .tunisian {
    border: 2px solid #1a237e;
    color: #1a237e;
  }
  
  .tunisian .price {
    color: #1a237e;
  }
  
  /* Stranger Card */
  .stranger {
    border: 2px solid #d32f2f;
    color: #d32f2f;
  }
  
  .stranger .price {
    color: #d32f2f;
  }
  </style>
  