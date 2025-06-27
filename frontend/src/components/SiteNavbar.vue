<template>
  <nav class="bg-white shadow-xl sticky top-0 z-50">
    <div class="mx-auto max-w-screen-xl px-4 sm:px-6 lg:px-8">
      <div class="flex h-20 items-center justify-between">
        <div class="flex-1 flex items-center gap-4">
          <div class="flex items-center font-bold text-lg" style="color: #265985;">
            <img src="../assets/logosite.png" alt="Logo" class="h-10 mr-2" />
            SITE 2025
          </div>
        </div>

        <div class="flex items-center gap-12">
          <ul :class="['nav-links flex items-center gap-6 text-sm', { 'nav-active': toggleMenu }]">
            <li>
              <router-link
                class="text-gray-500 hover:text-[#265985] transition-all duration-200"
                :class="{ 'border-b-2 border-[#265985] font-bold text-[#265985] text-base': $route.path === '/' }"
                to="/"
              >
                {{ $t('navbar.home') }}
              </router-link>
            </li>
            <li>
              <router-link
                class="text-gray-500 hover:text-[#265985] transition-all duration-200"
                :class="{ 'border-b-2 border-[#265985] font-bold text-[#265985] text-base': $route.path === '/schedule' }"
                to="/schedule"
              >
                {{ $t('navbar.program') }}
              </router-link>
            </li>
            <li>
              <router-link
                class="text-gray-500 hover:text-[#265985] transition-all duration-200"
                :class="{ 'border-b-2 border-[#265985] font-bold text-[#265985] text-base': $route.path === '/speaker' }"
                to="/speaker"
              >
                {{ $t('navbar.speakers') }}
              </router-link>
            </li>
            <li class="capitalize">
              <router-link
                class="text-gray-500 hover:text-[#265985] transition-all duration-200"
                :class="{ 'border-b-2 border-[#265985] font-bold text-[#265985] text-base': $route.path === '/sponsor' }"
                to="/sponsor"
              >
                {{ $t('navbar.sponsors') }}
              </router-link>
            </li>
            <li class="dropdown relative">
              <a
                href="#"
                class="text-gray-500 flex items-center"
                @click.prevent="toggleDropdown"
                @mouseenter="dropdownOpen = true"
                @mouseleave="dropdownOpen = false"
              >
                {{ $t('navbar.committees') }}
                <span class="chevron ml-1 text-xs">▾</span>
              </a>
              <ul
                :class="['dropdown-menu absolute top-full left-0 bg-white shadow-md rounded-md py-2 min-w-[200px] z-50', { 'hidden': !dropdownOpen }]"
                @mouseenter="dropdownOpen = true"
                @mouseleave="dropdownOpen = false"
              >
                <li>
                  <router-link
                    class="block px-4 py-2 text-gray-500 hover:bg-gray-100 hover:text-[#265985] transition-all duration-200"
                    :class="{ 'border-b-2 border-[#265985] font-bold text-[#265985] text-base': $route.path === '/scientific' }"
                    to="/scientific"
                  >
                    {{ $t('navbar.scientificCommittee') }}
                  </router-link>
                </li>
                <li>
                  <router-link
                    class="block px-4 py-2 text-gray-500 hover:bg-gray-100 hover:text-[#265985] transition-all duration-200"
                    :class="{ 'border-b-2 border-[#265985] font-bold text-[#265985] text-base': $route.path === '/honor' }"
                    to="/honor"
                  >
                    {{ $t('navbar.honorCommittee') }}
                  </router-link>
                </li>
                <li>
                  <router-link
                    class="block px-4 py-2 text-gray-500 hover:bg-gray-100 hover:text-[#265985] transition-all duration-200"
                    :class="{ 'border-b-2 border-[#265985] font-bold text-[#265985] text-base': $route.path === '/organizing' }"
                    to="/organizing"
                  >
                    {{ $t('navbar.organizingCommittee') }}
                  </router-link>
                </li>
              </ul>
            </li>
            <li>
              <router-link
                class="text-gray-500 hover:text-[#265985] transition-all duration-200"
                :class="{ 'border-b-2 border-[#265985] font-bold text-[#265985] text-base': $route.path === '/previous-editions' }"
                to="/previous-editions"
              >
                {{ $t('navbar.previousEditions') }}
              </router-link>
            </li>
            <li>
              <router-link
                class="text-gray-500 hover:text-[#265985] transition-all duration-200"
                :class="{ 'border-b-2 border-[#265985] font-bold text-[#265985] text-base': $route.hash === '#contact' }"
                to="/contactUs"
              >
                {{ $t('navbar.contact') }}
              </router-link>
            </li>
          </ul>

          <div class="flex items-center gap-4">
            <!-- Notification Icon -->
            <button class="relative text-gray-600 hover:text-[#265985] transition">
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V5a2 2 0 10-4 0v.083A6 6 0 004 11v3.159c0 .538-.214 1.055-.595 1.436L2 17h5m4 0a2 2 0 11-4 0h4z"></path>
              </svg>
              <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">3</span>
            </button>

            <!-- Language Switch -->
            <div class="lang-switch flex gap-2">
              <button
                @click="switchLanguage('fr')"
                :class="['rounded-md px-3 py-1.5 text-sm font-medium transition', currentLanguage === 'fr' ? 'bg-[#265985] text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200']"
              >
                <span class="full">Français</span>
                <span class="short hidden">Fr</span>
              </button>
              <button
                @click="switchLanguage('en')"
                :class="['rounded-md px-3 py-1.5 text-sm font-medium transition', currentLanguage === 'en' ? 'bg-[#265985] text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200']"
              >
                <span class="full">English</span>
                <span class="short hidden">En</span>
              </button>
            </div>

            <div class="block md:hidden">
              <button @click="toggleMenu = !toggleMenu" class="rounded-sm bg-gray-100 p-2 text-gray-600 hover:text-[#265985] transition">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
/* eslint-disable no-unused-vars */
import router from '@/router';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { useRoute } from 'vue-router';

const toggleMenu = ref(false);
const dropdownOpen = ref(false);
const { locale } = useI18n();
const currentLanguage = ref(locale.value);
const route = useRoute(); // Used in template via $route

const switchLanguage = (lang) => {
  currentLanguage.value = lang;
  locale.value = lang;
  document.cookie = `userLanguage=${lang}; path=/;`;
};

const toggleDropdown = () => {
  dropdownOpen.value = !dropdownOpen.value;
};
</script>

<style scoped>
html {
  scroll-behavior: smooth;
}

.nav-links {
  list-style: none;
}

.shadow-xl {
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2);
}

.dropdown:hover .dropdown-menu {
  display: block;
}

.dropdown-menu.hidden {
  display: none;
}

.border-b-2 {
  border-bottom-width: 2px;
  border-bottom-style: solid;
}

@media (max-width: 768px) {
  .nav-links {
    position: absolute;
    top: 80px;
    right: 0;
    background: white;
    flex-direction: column;
    align-items: flex-start;
    padding: 1.5rem;
    display: none;
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2);
    width: 100%;
    border-radius: 0 0 8px 8px;
    transition: all 0.3s ease-in-out;
  }

  .nav-links.nav-active {
    display: flex;
  }

  .nav-links li {
    width: 100%;
    padding: 0.5rem 0;
  }

  .nav-links li a {
    display: block;
    padding: 0.5rem 1rem;
    font-size: 1rem;
    transition: background-color 0.2s ease;
  }

  .nav-links li a:hover {
    background-color: #f1f5f9;
  }

  .dropdown-menu {
    position: static;
    box-shadow: none;
    width: 100%;
    padding-left: 1.5rem;
    border-left: 2px solid #eee;
    margin-top: 0.5rem;
    background: #f9fafb;
  }

  .dropdown-menu li a {
    padding: 0.4rem 1rem;
  }

  .chevron {
    display: none;
  }
}

@media (max-width: 600px) {
  .lang-switch .short {
    display: inline;
  }
  .lang-switch .full {
    display: none;
  }
}
</style>