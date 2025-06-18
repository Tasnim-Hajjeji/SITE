<template>
    <section class="conference-card">
      <div class="card-header">
        <h1>Site 2025</h1>
        <div class="action-buttons">
          <button class="delete-btn">
            <i class="fas fa-trash"></i> Delete
          </button>
          <button class="update-btn">
            <i class="fas fa-pen"></i> Update
          </button>
        </div>
      </div>
  
      <div class="card-body">
        <div class="card-left">
          <img class="speaker-img" :src="image" alt="speaker" />
        </div>
  
        <div class="card-right">
          <h2>SITE 2025</h2>
          <button class="status">En cours</button>
          <p class="description">
            descriton :Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
            labore et dolore magna aliqua.
          </p>
  
          <div class="info-row">
            <div class="info-item">
              <i class="fas fa-map-marker-alt"></i>
              <span class="label">place</span>
              <span class="value">Hammamet</span>
            </div>
  
            <div class="info-item">
              <i class="fas fa-clock"></i>
              <span class="label">Date</span>
              <span class="value">24 october</span>
            </div>
  
            <div class="info-item">
              <i class="fas fa-chart-bar"></i>
              <span class="label">Participants</span>
              <span class="value">200</span>
            </div>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script setup>
  const image = '/path/to/speaker-image.jpg'; // remplace par ton chemin réel
  </script>
  
  <style scoped>
  .conference-card {
    max-width: 1100px;
    margin: auto;
    padding: 2rem;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    font-family: 'Poppins', sans-serif;
    color: #2d2d5a;
  }
  
  .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
  }
  
  .card-header h1 {
    font-size: 2rem;
    font-weight: bold;
  }
  
  .action-buttons button {
    margin-left: 1rem;
    padding: 8px 16px;
    border-radius: 25px;
    border: 1px solid;
    cursor: pointer;
    font-weight: 500;
  }
  
  .delete-btn {
    border-color: #e53935;
    color: #e53935;
    background-color: #fff;
  }
  
  .update-btn {
    border-color: #3f51b5;
    color: #3f51b5;
    background-color: #fff;
  }
  
  .card-body {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
  }
  
  .card-left .speaker-img {
    width: 350px;
    height: 250px;
    border-radius: 20px;
    object-fit: cover;
  }
  
  .card-right {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }
  
  .card-right h2 {
    font-weight: bold;
    font-size: 1.5rem;
  }
  
  .status {
    margin: 8px 0;
    padding: 4px 16px;
    border-radius: 25px;
    border: 2px solid #3f51b5;
    color: #3f51b5;
    background-color: white;
    font-weight: 500;
    width: fit-content;
  }
  
  .description {
    margin: 1rem 0;
    color: #8b88a4;
    line-height: 1.6;
  }
  
  .info-row {
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
    margin-top: 1rem;
  }
  
  .info-item {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    color: #2d2d5a;
  }
  
  .info-item i {
    font-size: 1.4rem;
    color: #6b61cb;
    margin-bottom: 4px;
  }
  
  .label {
    font-weight: bold;
    text-transform: capitalize;
    color: #3f51b5;
  }
  
  .value {
    font-size: 0.95rem;
    color: #777;
  }
  </style>
  