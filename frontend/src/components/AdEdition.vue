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
  
          <!-- Dropdown Edition -->
          <div class="dropdown" @click="toggleDropdown">
            <button class="edition-btn">Edition ▼</button>
            <ul v-if="dropdownOpen" class="dropdown-menu">
              <li @click.stop="onEditOption('Edition 2024')">Edition 2024</li>
              <li @click.stop="onEditOption('Edition 2023')">Edition 2023</li>
              <li @click.stop="onEditOption('Edition 2022')">Edition 2022</li>
            </ul>
          </div>
        </div>
      </div>
  
      <div class="card-body">
  
        <div class="card-right">
          <h2>SITE 2025</h2>
          <button class="status">En cours</button>
          <p class="description">
            descriton :Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
            et dolore magna aliqua.
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
              <span class="value">24 October</span>
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
  import { ref } from 'vue';
  
  const dropdownOpen = ref(false);
  
  const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value;
  };
  
  const onEditOption = (option) => {
    dropdownOpen.value = false;
    alert(`Option sélectionnée : ${option}`);
  };
  </script>
  
<style scoped>
  @import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css");
  
  .conference-card {
    font-family: 'Segoe UI', sans-serif;
  }
  
  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    flex-wrap: wrap;
    gap: 1rem;
  }
  
  .card-header h1 {
    font-size: 1.7rem;
    color: black;
    font-weight: bold;
  }
  
  .action-buttons {
    display: flex;
    align-items: center;
    gap: 1rem;
    position: relative;
  }
  
  .action-buttons button {
    padding: 8px 16px;
    border-radius: 25px;
    border: 1px solid;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s ease;
  }
  
  .delete-btn {
    border-color: #e53935;
    color: #e53935;
    background-color: #fff;
  }
  
  .delete-btn:hover {
    background-color: #e53935;
    color: #fff;
  }
  
  .update-btn {
    border-color: #265985;
    color: #265985;
    background-color: #fff;
  }
  
  .update-btn:hover {
    background-color: #265985;
    color: #fff;
  }
  
  /* Edition dropdown styles */
  .dropdown {
    position: relative;
  }
  
  .edition-btn {
    padding: 8px 16px;
    border-radius: 25px;
    border: 1px solid #265985;
    background-color: white;
    color: #265985;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }
  
  .edition-btn:hover {
    background-color: #265985;
    color: white;
  }
  
  .dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    margin-top: 8px;
    background-color: white;
    border: 1px solid #ccc;
    border-radius: 10px;
    min-width: 160px;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
    list-style: none;
    padding: 0;
    z-index: 99;
  }
  
  .dropdown-menu li {
    padding: 10px 15px;
    cursor: pointer;
    transition: background 0.2s ease;
    font-size: 14px;
  }
  
  .dropdown-menu li:hover {
    background-color: #f0f4ff;
  }
  
  .card-body {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
    padding: 2rem;
    background-color: #f9f9f9;
    border-radius: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    animation: fadeIn 0.5s ease-in-out;
    border: 2px solid #265985;
  }
  
  .card-left .speaker-img {
    width: 350px;
    height: 250px;
    border-radius: 20px;
    object-fit: cover;
    transition: transform 0.3s ease;
  }
  
  .card-left .speaker-img:hover {
    transform: scale(1.05);
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
    border: 2px solid #265985;
    color: #265985;
    background-color: white;
    font-weight: 500;
    width: fit-content;
    transition: all 0.3s ease;
  }
  
  .status:hover {
    background-color: #265985;
    color: white;
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
    transition: transform 0.3s ease;
  }
  
  .info-item:hover {
    transform: translateY(-3px);
  }
  
  .info-item i {
    font-size: 1.2rem;
    color: #265985;
    margin-bottom: 4px;
  }
  
  .label {
    font-weight: bold;
    text-transform: capitalize;
    color: #265985;
  }
  
  .value {
    font-size: 0.9em;
    color: #777;
  }
  
  /* Responsive */
  @media (max-width: 768px) {
    .card-body {
      flex-direction: column;
      padding: 1.5rem;
    }
  
    .card-left .speaker-img {
      width: 100%;
      height: auto;
    }
  
    .action-buttons {
      flex-wrap: wrap;
    }
  }
  </style>
  