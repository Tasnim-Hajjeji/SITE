<template>
  <nav :class="['sidebar', { open: isOpen }]">
    <br><br><br>
    <ul>
      <router-link to="/admin" custom v-slot="{ navigate }">
        <li :class="{ active: selected === 'Dashbord' }" @click="handleNavigation('Dashbord', navigate)">
          <span class="icon"><i class="fas fa-home"></i></span>
          <span class="text">Editions</span>
        </li>
      </router-link>

      <router-link to="/admin/sponsor" custom v-slot="{ navigate }">
        <li :class="{ active: selected === 'Sponsors' }" @click="handleNavigation('Sponsors', navigate)">
          <span class="icon"><i class="fas fa-hand-holding-dollar"></i></span>
          <span class="text">Sponsors</span>
        </li>
      </router-link>

      <router-link to="/admin/partnair" custom v-slot="{ navigate }">
        <li :class="{ active: selected === 'Partenaires' }" @click="handleNavigation('Partenaires', navigate)">
          <span class="icon"><i class="fas fa-users"></i></span>
          <span class="text">Partners</span>
        </li>
      </router-link>

      <router-link to="/admin/speaker" custom v-slot="{ navigate }">
        <li :class="{ active: selected === 'Intervenants' }" @click="handleNavigation('Intervenants', navigate)">
          <span class="icon"><i class="fas fa-chalkboard-teacher"></i></span>
          <span class="text">Speakers</span>
        </li>
      </router-link>

      <router-link to="/admin/participant-card" custom v-slot="{ navigate }">
        <li :class="{ active: selected === 'participant-card' }" @click="handleNavigation('participant-card', navigate)">
          <span class="icon"><i class="fas fa-users-class"></i></span>
          <span class="text">Participants</span>
        </li>
      </router-link>

      <router-link to="/admin/committies" custom v-slot="{ navigate }">
        <li :class="{ active: selected === 'Committies' }" @click="handleNavigation('Committies', navigate)">
          <span class="icon"><i class="fas fa-comments"></i></span>
          <span class="text">Committies</span>
        </li>
      </router-link>
    </ul>
  </nav>
</template>

<script setup>
import { ref, defineExpose } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const selected = ref('Dashbord')
const isOpen = ref(false)

const select = (item) => {
  selected.value = item
  if (window.innerWidth < 768) isOpen.value = false
}
const showEditionAlert = () => {
  alert('Please select an edition first before navigating to other sections.')
}

const handleNavigation = (item, navigate = null) => {
  const selectedEditionId = localStorage.getItem('selectedEditionId')

  // If no edition is selected, always go to editions list
  if (!selectedEditionId) {
    showEditionAlert()
    select('Dashbord')
    router.push('/admin')
    return
  }

  // If edition is selected, proceed with normal navigation
  select(item)
  if (navigate) {
    navigate()
  }
}

const toggleSidebar = () => {
  isOpen.value = !isOpen.value
}

defineExpose({ toggleSidebar, isOpen })
</script>

<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css");

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
  font-size: 1.2rem; /* Légèrement agrandi pour un look plus luxueux */
  display: flex;
  align-items: center;
  color: #265985; /* Couleur unifiée */
}

.icon i {
  color: #265985; /* Assure que l'icône Font Awesome hérite de la couleur */
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

  li.active .icon i {
    color: #265985; /* Couleur de l'icône dans l'état actif */
  }
}
</style>