<template>
    <div class="container">
      <h1 class="title">Partners 2024</h1>
  
      <div class="actions">
        <button class="btn add">
          <span class="plus">+</span> Add Partners
        </button>
  
        <!-- Dropdown d'édition -->
        <div class="dropdown" @click="toggleDropdown">
          <button class="btn edit">Edition ▼</button>
          <ul v-if="dropdownOpen" class="dropdown-menu">
            <li @click="onEditOption('Edition 2024')">Edition 2024</li>
            <li @click="onEditOption('Edition 2023')">Edition 2023</li>
            <li @click="onEditOption('Edition 2022')">Edition 2022</li>
          </ul>
        </div>
      </div>
  
      <!-- Cartes partenaires -->
      <div class="grid">
        <div v-for="(partner, index) in partners" :key="index" class="card">
          <img :src="partner.logo" alt="logo" class="logo" />
          <h2 class="name">TDS</h2>
          <p class="desc">Technology Developpement</p>
  
          <div class="info">
            <i class="fas fa-phone icon"></i>
            <span>+12 345 6789 0</span>
          </div>
          <div class="info">
            <i class="fas fa-envelope icon"></i>
            <span>ahmadzayn@mail.com</span>
          </div>
  
          <div class="tools">
            <button class="icon-btn"><i class="fas fa-edit"></i></button>
            <button class="icon-btn"><i class="fas fa-trash"></i></button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: "PartenaireComponent",
    data() {
      return {
        dropdownOpen: false,
        partners: [
          { logo: "https://via.placeholder.com/100?text=Logo1" },
          { logo: "https://via.placeholder.com/100?text=Logo2" },
          { logo: "https://via.placeholder.com/100?text=Logo3" },
          { logo: "https://via.placeholder.com/100?text=Logo4" },
          { logo: "https://via.placeholder.com/100?text=Logo5" },
          { logo: "https://via.placeholder.com/100?text=Logo6" },
        ],
      };
    },
    methods: {
      toggleDropdown() {
        this.dropdownOpen = !this.dropdownOpen;
      },
      onEditOption(option) {
        this.dropdownOpen = false;
        alert(`Option sélectionnée : ${option}`);
      },
    },
  };
  </script>
  
  <style scoped>
  @import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css");
  
  .container {
    padding: 24px;
    font-family: "Segoe UI", sans-serif;
  }
  
  .title {
    font-size: 24px;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 20px;
  }
  
  .actions {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 10px;
  }
  
  .btn {
    padding: 10px 20px;
    border-radius: 9999px;
    font-weight: 500;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s ease, transform 0.2s ease;
  }
  
  .add {
    background-color: #265985;
    color: white;
    border: none;
  }
  
  .add:hover {
    background-color: #1e476b;
    transform: scale(1.05);
  }
  
  .edit {
    border: 1px solid #265985;
    color: #265985;
    background: white;
  }
  
  .dropdown {
    position: relative;
  }
  
  .dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    margin-top: 5px;
    background: white;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
    z-index: 100;
    list-style: none;
    padding: 0;
    min-width: 160px;
    overflow: hidden;
  }
  
  .dropdown-menu li {
    padding: 10px 15px;
    cursor: pointer;
    transition: background 0.2s;
    font-size: 14px;
  }
  
  .dropdown-menu li:hover {
    background-color: #f0f4ff;
  }
  
  .grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
  }
  
  .card {
    background: rgb(254, 252, 252);
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    padding: 16px;
    border: 2px solid #265985;
    
    text-align: center;
    position: relative;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  
  .card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
  }
  
  .logo {
    height: 60px;
    object-fit: contain;
    margin-bottom: 16px;
    transition: transform 0.3s ease;
  }
  
  .card:hover .logo {
    transform: scale(1.1);
  }
  
  .name {
    font-weight: 600;
    color: #1f2937;
  }
  
  .desc {
    font-size: 14px;
    color: #9ca3af;
    margin-bottom: 16px;
  }
  
  .info {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 14px;
    color: #4b5563;
    margin-bottom: 8px;
  }
  
  .icon {
    color: #8b5cf6;
  }
  
  .tools {
    position: absolute;
    top: 8px;
    right: 8px;
    display: flex;
    gap: 8px;
  }
  
  .icon-btn {
    background: transparent;
    border: none;
    color: #6b7280;
    cursor: pointer;
    font-size: 16px;
  }
  
  .icon-btn:hover {
    color: #852c26;
  }
  
  /* Responsive */
  @media (max-width: 480px) {
    .btn {
      width: 100%;
      justify-content: center;
    }
  
    .actions {
      flex-direction: column;
      align-items: stretch;
    }
  }
  </style>
  