<template>
  <nav class="navbar">
    <div class="container">
      <div class="logo" style="color:#005a90;">
        <img src="../assets/logosite.png" alt="Logo" class="logo-img" />
        SITE 2025
      </div>
      <ul :class="['nav-links', { 'nav-active': toggleMenu }]">
        <li><router-link to="/">{{ $t('navbar.home') }}</router-link></li>
        <li><router-link to="/schedule">{{ $t('navbar.program') }}</router-link></li>
        <li><router-link to="/speaker">{{ $t('navbar.speakers') }}</router-link></li>
        <li class="dropdown">
          <a href="#">
            {{ $t('navbar.committees') }}
            <span class="chevron">&#9662;</span>
          </a>
          <ul class="dropdown-menu">
            <li><router-link to="/scientific">{{ $t('navbar.scientificCommittee') }}</router-link></li>
            <li><router-link to="/honor">{{ $t('navbar.honorCommittee') }}</router-link></li>
            <li><router-link to="/organizing">{{ $t('navbar.organizingCommittee') }}</router-link></li>
          </ul>
        </li>
        <li><router-link to="/previous-editions">{{ $t('navbar.previousEditions') }}</router-link></li>
        <li><a href="#contact">{{ $t('navbar.contact') }}</a></li>
      </ul>
      <div class="lang-switch">
        <button @click="switchLanguage('fr')" :class="{ 'active': currentLanguage === 'fr' }">
          Français
        </button>
        <button @click="switchLanguage('en')" :class="{ 'active': currentLanguage === 'en' }">
          English
        </button>
      </div>
      <div class="burger" @click="toggleMenu = !toggleMenu">
        <div></div>
        <div></div>
        <div></div>
      </div>
    </div>
  </nav>
</template>


<script setup>
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
const toggleMenu = ref(false);
const { locale } = useI18n();
const currentLanguage = ref(locale.value);

const switchLanguage = (lang) => {
  currentLanguage.value = lang;
  locale.value = lang;
  localStorage.setItem('userLanguage', lang);
};
</script>

<style scoped>
html {
  scroll-behavior: smooth;
}

.navbar {
  font-family: 'Segoe UI', sans-serif;
}

.logo {
  display: flex;
  align-items: center;
  font-weight: bold;
  font-size: 1.2rem;
}

.logo-img {
  height: 30px;
  margin-right: 10px;
}

.chevron {
  margin-left: 5px;
  font-size: 0.6em;
}

.dropdown {
  position: relative;
}

.dropdown-menu {
  list-style: none;
  padding: 0;
  margin: 0;
  position: absolute;
  top: 100%;
  left: 0;
  background: white;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  padding: 0.5rem 0;
  list-style: none;
  min-width: 200px;
  z-index: 1000;
}

.dropdown-menu li a {
  display: block;
  padding: 0.5rem 1rem;
  color: black;
  text-decoration: none;
  white-space: nowrap;
}

.dropdown-menu li a:hover {
  background-color: #f1f1f1;
}

.navbar {
  background: white;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  padding: 1rem 0;
  position: sticky;
  top: 0;
  z-index: 999;
}

.container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  max-width: 1200px;
  margin: auto;
  padding: 0 1rem;
}

.nav-links {
  list-style: none;
  display: flex;
  gap: 1rem;
}

.nav-links li a {
  text-decoration: none;
  color: black;
  font-weight: 500;
}

.lang-switch button {
  margin-left: 0.5rem;
  background: none;
  border: 1px solid #ccc;
  padding: 0.3rem 0.6rem;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.lang-switch button:hover {
  background-color: #f0f0f0;
}

.lang-switch .active {
  background-color: #005a90;
  color: white;
  border-color: #005a90;
}

.burger {
  display: none;
  flex-direction: column;
  cursor: pointer;
  gap: 5px;
}

.burger div {
  width: 25px;
  height: 3px;
  background-color: black;
}

@media (max-width: 768px) {
  .nav-links {
    position: absolute;
    right: 0;
    top: 60px;
    background: white;
    flex-direction: column;
    align-items: flex-start;
    padding: 1rem;
    display: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  .nav-links.nav-active {
    display: flex;
  }

  .burger {
    display: flex;
  }

  .dropdown-menu {
    position: static;
    box-shadow: none;
  }

  .chevron {
    display: none;
  }

}

/* Desktop dropdown behavior */
@media (min-width: 769px) {

  .dropdown-menu {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 0.5rem 0;
    z-index: 1000;
    min-width: 200px;
  }

  .dropdown:hover .dropdown-menu {
    display: block;
  }
}

/* Mobile: dropdown menu always visible under "Communities" */
@media (max-width: 768px) {
  .dropdown-menu {
    padding-left: 1rem;
    border-left: 2px solid #eee;
    margin-top: 0.3rem;
  }

  .dropdown-menu li a {
    display: block;
    padding: 0.4rem 0;
    color: #333;
  }

  .submenu-title {
    font-weight: bold;
    color: #1a73e8;
    padding: 0.4rem 0;
  }
}
</style>
