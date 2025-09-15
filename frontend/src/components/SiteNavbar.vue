<template>
  <nav class="bg-white shadow-xl sticky top-0 z-50">
    <div class="mx-auto max-w-screen-xl px-4 sm:px-6 lg:px-8">
      <div class="flex h-20 items-center justify-between">
        <div class="flex-1 flex items-center gap-4">
          <div class="flex items-center font-bold text-lg site-title" style="color: #265985;">
            <img src="../assets/x.png" alt="Logo" class="h-10 mr-2" />
            Conference
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
            <!-- Notification Icon with Dropdown -->
            <div class="relative">
              <button
                class="relative text-gray-600 hover:text-[#265985] transition"
                @click="toggleNotificationDropdown"
              >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V5a2 2 0 10-4 0v.083A6 6 0 004 11v3.159c0 .538-.214 1.055-.595 1.436L2 17h5m4 0a2 2 0 11-4 0h4z"></path>
                </svg>
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">{{ notifications.length }}</span>
              </button>
              <ul
                :class="['notification-dropdown absolute top-full right-0 bg-white shadow-md rounded-md py-2 min-w-[400px] z-50', { 'hidden': !notificationDropdownOpen }]"
              >
                <li class="p-3 text-xs opacity-60 tracking-wide">Recent Notifications</li>
                <li v-for="(notification, index) in notifications" :key="index" class="list-row flex items-center gap-3 p-3 hover:bg-gray-100 transition-all duration-200 border-t-[1px] border-t-gray-500">
                  <div class="icon-container">
                    <img class="size-10 rounded-box" src="../assets/notification.png" alt="Notification Icon" />
                  </div>
                  <div class="list-col-grow">
                    <div class="font-semibold text-gray-800">{{ notification.title }}</div>
                    <div class="text-xs text-gray-500 mt-[1px]">{{ notification.message }}</div>
                    <div class="text-xs opacity-60 mt-1">{{ formatDate(notification.activated_at) }}</div>
                  </div>
                </li>
              </ul>
            </div>

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
import NotificationService from '@/services/NotificationService'
import router from '@/router';
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import { useRoute } from 'vue-router';

const toggleMenu = ref(false);
const dropdownOpen = ref(false);
const notificationDropdownOpen = ref(false);
const { locale } = useI18n();
const currentLanguage = ref(locale.value);
const route = useRoute();

// Sample notifications data
const notifications = ref([]);

onMounted(() => {
  NotificationService.getActivatedNotifs().then(response => {
      notifications.value = response.data
    }).catch(error => {console.log(error)})
})

const switchLanguage = (lang) => {
  currentLanguage.value = lang;
  locale.value = lang;
  document.cookie = `userLanguage=${lang}; path=/;`;
};

const toggleDropdown = () => {
  dropdownOpen.value = !dropdownOpen.value;
  notificationDropdownOpen.value = false; // Close notification dropdown when opening committees
};

const toggleNotificationDropdown = () => {
  notificationDropdownOpen.value = !notificationDropdownOpen.value;
  dropdownOpen.value = false; // Close committees dropdown when opening notifications
};

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
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

.dropdown-menu.hidden,
.notification-dropdown.hidden {
  display: none;
}

.border-b-2 {
  border-bottom-width: 2px;
  border-bottom-style: solid;
}

.notification-dropdown {
  background: white;
  border: 1px solid #ccc;
  border-radius: 8px;
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
  z-index: 100;
  list-style: none;
  padding: 0;
  min-width: 400px;
  overflow: hidden;
}

.notification-dropdown li {
  cursor: pointer;
  transition: background 0.2s;
  font-size: 14px;
}

.notification-dropdown li:hover {
  background-color: #f0f4ff;
}

.list-row {
  display: flex;
  align-items: center;
  gap: 12px;
}

.list-col-grow {
  flex-grow: 1;
  flex-shrink: 1;
  overflow: hidden;
}

.list-col-grow .text-xs {
  white-space: normal;
  word-wrap: break-word;
}

.icon-container {
  flex-shrink: 0;
  width: 40px;
  height: 40px;
}

.icon-container img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.rounded-box {
  border-radius: 8px;
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

  .dropdown-menu,
  .notification-dropdown {
    position: static;
    box-shadow: none;
    width: 100%;
    padding-left: 1.5rem;
    border-left: 2px solid #eee;
    margin-top: 0.5rem;
    background: #f9fafb;
  }

  .dropdown-menu li a,
  .notification-dropdown li {
    padding: 0.4rem 1rem;
  }

  .chevron {
    display: none;
  }
}

@media (max-width: 768px) {
  .nav-links li a,
  .dropdown-menu li a,
  .notification-dropdown li {
    font-size: 0.85rem;
  }

  /* تصغير حجم أزرار اللغة */
  .lang-switch button {
    padding: 0.3rem 0.6rem;
    font-size: 0.85rem;
  }
}
@media (max-width: 768px) {
  .nav-links li a {
    font-size: 0.875rem; /* صغرنا الخط شوية (14px) */
    padding: 0.3rem 0.8rem; /* نقصنا padding أفقي ورأسي */
  }

  nav .flex.h-20 {
    height: 56px; /* نقصنا ارتفاع النافبار */
  }

  .nav-links li {
    padding: 0.3rem 0; /* نقصنا padding الأفقي والرأسي للروابط */
  }

  /* لو حبيت تصغر حجم اللوجو */
  nav img.h-10 {
    height: 32px; /* بدل 40px الى 32px */
  }
  .site-title {
    font-size: 0.85rem; /* تقريبًا 14px */
  }
}

</style>