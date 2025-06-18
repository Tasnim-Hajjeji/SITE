<template>
    <div class="container">
      <h1 class="title">Speakers 2024</h1>
  
      <div class="actions">
        <button class="btn add">
          <span class="plus">+</span> Add speaker
        </button>
  
        <!-- Dropdown -->
        <div class="dropdown" @click="toggleDropdown">
          <button class="btn edit">Edition ▼</button>
          <ul v-if="dropdownOpen" class="dropdown-menu">
            <li @click="selectEdition('SITE 2025')">SITE 2025</li>
            <li @click="selectEdition('SITE 2024')">SITE 2024</li>
            <li @click="selectEdition('SITE 2023')">SITE 2023</li>
            <li @click="selectEdition('SITE 2022')">SITE 2022</li>
          </ul>
        </div>
      </div>
  
      <div class="grid">
        <div class="card" v-for="n in 6" :key="n">
          <h2 class="name">
            hakim sadkaoui <img class="flag" src="https://flagcdn.com/tn.svg" alt="TN" />
          </h2>
          <p class="desc"><strong>Etablissement :</strong> iset bizerte</p>
          <p class="desc"><strong>intervention :</strong> Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
          <p class="info">
            📌 <span class="count">6</span> Intervention
          </p>
          <div class="info">
            <img class="avatar" src="https://randomuser.me/api/portraits/men/32.jpg" alt="avatar" />
            <span>SITE 2025</span>
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
    name: "SpeakersComponent",
    data() {
      return {
        dropdownOpen: false,
      };
    },
    methods: {
      toggleDropdown() {
        this.dropdownOpen = !this.dropdownOpen;
      },
      selectEdition(edition) {
        this.dropdownOpen = false;
        alert(`Selected: ${edition}`);
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
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
  }
  
  .card {
    background: #fefcfc;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    padding: 16px;
    border: 2px solid #265985;
    position: relative;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  
  .card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
  }
  
  .name {
    font-weight: 600;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
  }
  
  .flag {
    width: 20px;
  }
  
  .desc {
    font-size: 14px;
    color: #4b5563;
    margin-bottom: 8px;
  }
  
  .info {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #374151;
    margin-bottom: 8px;
  }
  
  .avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
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
  