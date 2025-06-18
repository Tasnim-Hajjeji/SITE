<template>
    <nav :class="['sidebar', { open: isOpen }]">
      <br><br><br>
      <ul>
        <router-link to="/admin" custom v-slot="{ navigate }">
          <li :class="{ active: selected === 'Dashbord' }" @click="select('Dashbord'); navigate()">
            <span class="icon">🏠</span>
            <span class="text">Editions</span>
          </li>
        </router-link>
  
        <li :class="{ active: selected === 'Sponsors' }" @click="select('Sponsors')">
          <span class="icon">💸</span>
          <span class="text">Sponsors</span>
        </li>
  
        <router-link to="/admin/partnair" custom v-slot="{ navigate }">
          <li :class="{ active: selected === 'Partenaires' }" @click="select('Partenaires'); navigate()">
            <span class="icon">👤</span>
            <span class="text">Partners</span>
          </li>
        </router-link>
  
        <router-link to="/admin/speaker" custom v-slot="{ navigate }">
          <li :class="{ active: selected === 'Intervenants' }" @click="select('Intervenants'); navigate()">
            <span class="icon">🧑‍🏫</span>
            <span class="text">Speakers</span>
          </li>
        </router-link>
  
        <router-link to="/admin/participant-card" custom v-slot="{ navigate }">
          <li :class="{ active: selected === 'participant-card' }" @click="select('participant-card'); navigate()">
          <span class="icon">🧑‍🤝‍🧑</span>
          <span class="text">Participants</span>
        </li>
        </router-link>
        <li :class="{ active: selected === 'Contact' }" @click="select('Contact')">
          <span class="icon">💬</span>
          <span class="text">Committies</span>
        </li>
      </ul>
    </nav>
  </template>
  
  <script setup>
  import { ref, defineExpose } from 'vue'
  
  const selected = ref('Dashbord')
  const isOpen = ref(false)
  
  const select = (item) => {
    selected.value = item
    if (window.innerWidth < 768) isOpen.value = false
  }
  
  const toggleSidebar = () => {
    isOpen.value = !isOpen.value
  }
  
  defineExpose({ toggleSidebar, isOpen })
  </script>
  
  <style scoped>
  .sidebar {
    width: 220px;
    background-color: white;
    height: 100vh;
    padding-top: 2rem;
    font-family: 'Segoe UI', sans-serif;
    position: fixed;
    top: 0;
    left: 0;
    transform: translateX(-100%);
    transition: transform 0.3s ease-in-out;
    z-index: 999;
  }
  
  /* Sidebar ouverte */
  .sidebar.open {
    transform: translateX(0);
  }
  
  ul {
    list-style: none;
    padding: 0;
    margin: 0;
  }
  
  li {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #000;
    opacity: 0.9;
    padding: 0.9rem 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.95rem;
  }
  
  li:hover {
    opacity: 1;
  }
  
  li.active {
    background-color: #265985;
    color: white;
    border-top-left-radius: 30px;
    border-bottom-left-radius: 30px;
    font-weight: 500;
  }
  
  .icon {
    font-size: 1.1rem;
    display: flex;
    align-items: center;
  }
  
  .text {
    text-transform: capitalize;
    font-size: 0.95rem;
  }
  
  @media (min-width: 768px) {
    .sidebar {
      transform: translateX(0);
      background-color: #265985;
    }
  
    li {
      color: white;
    }
  
    li.active {
      background-color: white;
      color: #265985;
    }
  
    li.active .icon {
      color: #265985;
    }
  }
  </style>
  