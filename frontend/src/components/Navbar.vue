<template>
  <nav class="admin-navbar">
    <div class="navbar-left">
      <img src="@/assets/logosite.png" alt="Logo" class="logo" />
      <span class="site-title">SITE 202X</span>
    </div>

    <div class="navbar-center">
      <div class="search-container">
        <input
          v-model="searchQuery"
          @input="handleSearch"
          @focus="showResults = true"
          @blur="onBlur"
          type="text"
          class="search-input"
          placeholder="Search for any person..."
        />
        <div v-if="searchLoading" class="search-loading">Searching...</div>
        <div v-if="showResults && searchResults.length > 0" class="search-results">
          <div
            v-for="result in searchResults"
            :key="`${result.type}-${result.id}`"
            class="search-result-item"
            @mousedown.prevent="navigateToResult(result)"
          >
            <div class="result-type-badge" :class="result.type">
              {{ getTypeLabel(result.type) }}
            </div>
            <div class="result-content">
              <div class="result-title">{{ result.name }}</div>
              <div class="result-subtitle">{{ result.description }}</div>
            </div>
          </div>
        </div>
        <div
          v-if="showResults && searchResults.length === 0 && searchQuery.length > 1"
          class="search-empty"
        >
          No results found for "{{ searchQuery }}"
        </div>
      </div>
    </div>

    <div class="navbar-right">
      <img src="@/assets/adminLogo2.png" alt="Notifications" class="notification-icon" />
      <span class="admin-name">Admin</span>
      <button class="logout-button" @click="showLogoutModal = true">
        <i class="fas fa-sign-out-alt"></i>
        <span class="logout-text">Déconnexion</span>
      </button>
    </div>

    <!-- Logout Confirmation Modal -->
    <div v-if="showLogoutModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-[1001]">
      <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Confirmer la déconnexion</h2>
        <p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir vous déconnecter ?</p>
        <div class="flex justify-end gap-4">
          <button
            class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition"
            @click="showLogoutModal = false"
          >
            Annuler
          </button>
          <button
            class="px-4 py-2 bg-blue-900 text-white rounded hover:bg-blue-700 transition"
            @click="confirmLogout"
          >
            Déconnexion
          </button>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from '@/plugins/axios';

const router = useRouter();
const searchQuery = ref('');
const searchResults = ref([]);
const showResults = ref(false);
const searchLoading = ref(false);
const showLogoutModal = ref(false); // Control modal visibility
let searchTimeout = null;

const getTypeLabel = (type) => {
  const labels = {
    speaker: 'Speaker',
    sponsor: 'Sponsor',
    participant: 'Participant',
    committee: 'Committee',
    partner: 'Partner',
  };
  return labels[type] || type;
};

const handleSearch = () => {
  clearTimeout(searchTimeout);

  if (searchQuery.value.length < 2) {
    searchResults.value = [];
    return;
  }

  searchTimeout = setTimeout(async () => {
    try {
      searchLoading.value = true;
      const editionId = localStorage.getItem('selectedEditionId');
      if (!editionId) {
        console.error('No edition ID found in localStorage');
        return;
      }

      const response = await axios.get('/global-search', {
        params: {
          query: searchQuery.value,
          edition_id: editionId,
        },
      });

      searchResults.value = response.data;
    } catch (error) {
      console.error('Search error:', error);
      searchResults.value = [];
    } finally {
      searchLoading.value = false;
    }
  }, 300);
};

const navigateToResult = (result) => {
  showResults.value = false;
  router.push({
    path: result.url,
    query: {
      highlight: result.id,
      highlightType: result.type,
    },
  });
};

const onBlur = () => {
  setTimeout(() => {
    showResults.value = false;
  }, 200);
};

const confirmLogout = async () => {
  try {
    // Call your logout API endpoint
    await axios.post(
      '/admin/logout',
      {},
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('admin_token')}`,
        },
      }
    );

    // Clear local storage and redirect
    localStorage.removeItem('admin_token');
    localStorage.removeItem('selectedEditionId');
    router.push('/'); // Redirect to home page
  } catch (error) {
    console.error('Logout failed:', error);
    // Still clear local storage even if API call fails
    localStorage.removeItem('admin_token');
    localStorage.removeItem('selectedEditionId');
    router.push('/');
  }
  showLogoutModal.value = false; // Close modal after logout
};
</script>

<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css");

.admin-navbar {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 60px;
  padding: 0 25px;
  background-color: white;
  border-bottom: 2px solid #e0e0e0;
  font-family: 'Segoe UI', sans-serif;
}

.navbar-left {
  display: flex;
  align-items: center;
  gap: 10px;
}

.logo {
  height: 30px;
  width: 30px;
}

.site-title {
  font-weight: 700;
  font-size: 18px;
  color: #002b45;
}

.navbar-center {
  flex: 1;
  display: flex;
  justify-content: center;
}

.search-container {
  position: relative;
  width: 300px;
}

.search-input {
  width: 100%;
  height: 32px;
  padding: 6px 12px;
  border: 1px solid #ccc;
  border-radius: 16px;
  font-size: 14px;
  outline: none;
  transition: all 0.3s ease;
}

.search-input:focus {
  border-color: #265985;
  box-shadow: 0 0 0 2px rgba(38, 89, 133, 0.2);
}

.search-results {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  background: white;
  border: 1px solid #ddd;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  z-index: 1001;
  max-height: 400px;
  overflow-y: auto;
  margin-top: 4px;
}

.search-result-item {
  padding: 12px;
  border-bottom: 1px solid #eee;
  cursor: pointer;
  display: flex;
  gap: 12px;
  align-items: center;
  transition: background-color 0.2s;
}

.search-result-item:hover {
  background-color: #f8f9fa;
}

.result-type-badge {
  font-size: 10px;
  text-transform: uppercase;
  padding: 2px 6px;
  border-radius: 10px;
  font-weight: 600;
  color: white;
  flex-shrink: 0;
}

.result-type-badge.speaker {
  background-color: #4a6baf;
}

.result-type-badge.sponsor {
  background-color: #38a169;
}

.result-type-badge.participant {
  background-color: #9f7aea;
}

.result-type-badge.committee {
  background-color: #ed8936;
}

.result-type-badge.partner {
  background-color: #e14272;
}

.result-content {
  flex-grow: 1;
  overflow: hidden;
}

.result-title {
  font-weight: 500;
  font-size: 14px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  margin-bottom: 2px;
}

.result-subtitle {
  font-size: 12px;
  color: #666;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.search-loading,
.search-empty {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  padding: 12px;
  background: white;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 14px;
  color: #666;
  text-align: center;
  z-index: 1001;
}

.navbar-right {
  display: flex;
  align-items: center;
  gap: 15px;
}

.notification-icon {
  width: 22px;
  height: 22px;
}

.admin-name {
  font-size: 16px;
  color: #333;
}

.logout-button {
  background: #2e98b3;
  border: none;
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  color: white;
  font-size: 14px;
  padding: 4px 12px;
  border-radius: 8px;
  transition: background-color 0.3s ease;
}

.logout-button:hover {
  background-color: #1e4569;
}

.logout-button i {
  font-size: 16px;
}

.logout-text {
  display: inline;
}

@media (max-width: 768px) {
  .navbar-left {
    display: none;
  }

  .navbar-center {
    flex: 1;
    display: flex;
    justify-content: center;
  }

  .search-container {
    width: 200px;
  }

  .search-input {
    display: block;
  }

  .logout-text {
    display: none; /* Hide text on mobile, show only icon */
  }

  .logout-button {
    padding: 6px 8px;
  }
}
</style>