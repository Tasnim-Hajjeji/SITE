<template>
    <nav :class="['sidebar', { open: isOpen }]">

        <br><br><br>
        <ul>
            <router-link to="/admin">
                <li :class="{ active: selected === 'Dashbord' }" @click="select('Dashbord')">
                    <span class="icon">🏠</span>
                    <span class="text">Editions</span>
                </li>
            </router-link>
            <li :class="{ active: selected === 'Sponsors' }" @click="select('Sponsors')">
                <span class="icon">💸</span>
                <span class="text">Sponsors</span>
            </li>
            <router-link to="/admin/partnair">
                <li :class="{ active: selected === 'Partenaires' }" @click="select('Partenaires')">
                    <span class="icon">👤</span>
                    <span class="text">Partners</span>
                </li>
            </router-link>
            <router-link to="/admin/speaker">
                <li :class="{ active: selected === 'Intervenants' }" @click="select('Intervenants')">
                    <span class="icon">🧑‍🏫</span>
                    <span class="text">Speakers</span>
                </li>
            </router-link>
            <li :class="{ active: selected === 'participants' }" @click="select('participants')">
                <span class="icon">🧑‍🤝‍🧑</span>
                <span class="text">participants</span>
            </li>
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
    text-decoration: none;
}

/* Sidebar ouverte */
.sidebar.open {
    transform: translateX(0);
    text-decoration: none;
}

ul {
    list-style: none;
    padding: 0;
    margin: 0;
    text-decoration: none;
}

li {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #000000;
    opacity: 0.9;
    padding: 0.9rem 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.95rem;
    text-decoration: none;
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
    text-decoration: none;
}

li.active .icon {
    color: #265985;
    text-decoration: none;
}

.icon {
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    text-decoration: none;
}

.text {
    text-transform: capitalize;
    font-size: 0.95rem;
    text-decoration: none;

}

router-link {
    text-decoration: none;
    color: inherit;
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
}
</style>