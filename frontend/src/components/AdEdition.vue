<template>
  <section class="conference-card" v-if="!isLoading && edition">
    <div class="card-header">
      <h1>{{ edition.name }}</h1>
      <div class="action-buttons">
        <button class="delete-btn" @click="confirmDelete">
          <i class="fas fa-trash"></i> Delete
        </button>
        <button class="update-btn" @click="showUpdateModal = true">
          <i class="fas fa-pen"></i> Update
        </button>
        <div class="dropdown" @click.stop="toggleDropdown">
          <button class="edition-btn">Edition ▼</button>
          <ul v-if="dropdownOpen" class="dropdown-menu">
            <li v-for="ed in editions" :key="ed.id" @click="selectEdition(ed.id)">
              {{ ed.name }}
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="card-body">
      <div class="card-right">
        <h2>{{ edition.name.toUpperCase() }}</h2>
        <button class="status" :class="{ 'current': isCurrentEdition }">
          {{ editionStatus }}
        </button>
        <p class="description">
          {{ edition.description_fr || 'No description available' }}
        </p>
        <div class="info-row">
          <div class="info-item">
            <i class="fas fa-map-marker-alt"></i>
            <span class="label">Place</span>
            <span class="value">{{ edition.place || 'Not specified' }}</span>
          </div>
          <div class="info-item">
            <i class="fas fa-clock"></i>
            <span class="label">Date</span>
            <span class="value">{{ formattedDateRange }}</span>
          </div>
          <div class="info-item">
            <i class="fas fa-chart-bar"></i>
            <span class="label">Participants</span>
            <span class="value">200</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Update Modal -->
    <div v-if="showUpdateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
      <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-md font-poppins max-h-[90vh] overflow-y-auto">
        <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">Update Edition</h3>
        <div class="space-y-4">
          <div>
            <label for="update_name" class="block text-sm font-medium text-gray-700">Name</label>
            <input
              v-model="editionForm.name"
              id="update_name"
              type="text"
              placeholder="Enter edition name"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required
            />
          </div>
          <div>
            <label for="update_description_fr" class="block text-sm font-medium text-gray-700">French Description</label>
            <textarea
              v-model="editionForm.description_fr"
              id="update_description_fr"
              placeholder="Enter French description"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              rows="4"
              required
            ></textarea>
          </div>
          <div>
            <label for="update_description_en" class="block text-sm font-medium text-gray-700">English Description</label>
            <textarea
              v-model="editionForm.description_en"
              id="update_description_en"
              placeholder="Enter English description"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              rows="4"
              required
            ></textarea>
          </div>
          <div>
            <label for="update_start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
            <input
              v-model="editionForm.start_date"
              id="update_start_date"
              type="date"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required
            />
          </div>
          <div>
            <label for="update_end_date" class="block text-sm font-medium text-gray-700">End Date</label>
            <input
              v-model="editionForm.end_date"
              id="update_end_date"
              type="date"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required
            />
          </div>
          <div>
            <label for="update_place" class="block text-sm font-medium text-gray-700">Place</label>
            <input
              v-model="editionForm.place"
              id="update_place"
              type="text"
              placeholder="Enter location"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required
            />
          </div>
          <div>
            <label for="update_dossier_sponso" class="block text-sm font-medium text-gray-700">Sponsorship File</label>
            <input
              id="update_dossier_sponso"
              type="file"
              @change="(e) => editionForm.dossier_sponso = e.target.files[0]"
              accept=".pdf,.docx,.doc"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
            />
          </div>
          <div v-if="error" class="p-2 bg-red-100 text-red-700 rounded-md flex items-center">
            <span class="mr-1">!</span> {{ error }}
          </div>
        </div>
        <div class="mt-6 flex justify-end space-x-3">
          <button
            @click="showUpdateModal = false"
            type="button"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200"
          >
            Cancel
          </button>
          <button
            @click="updateEdition"
            type="button"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200"
          >
            Save Changes
          </button>
        </div>
      </div>
    </div>
  </section>

  <div v-if="isLoading" class="loading">
    Loading edition data...
  </div>
  <div v-if="error" class="error-message">
    {{ error }}
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import EditionService from '@/services/EditionService';

const route = useRoute();
const router = useRouter();

const editionId = ref(route.params.editionId || localStorage.getItem('selectedEditionId'));
const editions = ref([]);
const edition = ref(null);
const isLoading = ref(true);
const error = ref(null);
const dropdownOpen = ref(false);
const showUpdateModal = ref(false);

const editionForm = ref({
  name: '',
  description_fr: '',
  description_en: '',
  start_date: '',
  end_date: '',
  place: '',
  dossier_sponso: null
});

const frenchMonths = [
  'janvier', 'février', 'mars', 'avril', 'mai', 'juin',
  'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'
];

onMounted(async () => {
  await fetchEditions();
  await fetchEditionDetails();
});

const fetchEditions = async () => {
  try {
    const response = await EditionService.getAllEditions();
    editions.value = response.data;
  } catch (err) {
    error.value = 'Failed to load editions: ' + err.message;
    console.error(err);
  }
};

const fetchEditionDetails = async () => {
  isLoading.value = true;
  error.value = null;
  try {
    const response = await EditionService.getEdition(editionId.value);
    edition.value = response.data;
    editionForm.value = { ...response.data };
  } catch (err) {
    error.value = 'Failed to load edition details: ' + err.message;
    console.error(err);
  } finally {
    isLoading.value = false;
  }
};

const isCurrentEdition = computed(() => {
  if (!edition.value?.end_date) return false;
  const today = new Date();
  const endDate = new Date(edition.value.end_date);
  return endDate >= today;
});

const editionStatus = computed(() => {
  return isCurrentEdition.value ? 'En Cours' : 'Terminé';
});

const formattedDateRange = computed(() => {
  if (!edition.value?.start_date || !edition.value?.end_date) return 'Dates not specified';
  const start = new Date(edition.value.start_date);
  const end = new Date(edition.value.end_date);
  if (start.getMonth() === end.getMonth() && start.getFullYear() === end.getFullYear()) {
    return `${start.getDate()} - ${end.getDate()} ${frenchMonths[start.getMonth()]} ${start.getFullYear()}`;
  } else if (start.getFullYear() === end.getFullYear()) {
    return `${start.getDate()} ${frenchMonths[start.getMonth()]} - ${end.getDate()} ${frenchMonths[end.getMonth()]} ${start.getFullYear()}`;
  }
  return `${start.getDate()} ${frenchMonths[start.getMonth()]} ${start.getFullYear()} - ${end.getDate()} ${frenchMonths[end.getMonth()]} ${end.getFullYear()}`;
});

const toggleDropdown = () => {
  dropdownOpen.value = !dropdownOpen.value;
};

const selectEdition = (id) => {
  dropdownOpen.value = false;
  router.push({ name: 'AdEdition', params: { editionId: id } });
  editionId.value = id;
  fetchEditionDetails();
};

const confirmDelete = async () => {
  if (confirm('Are you sure you want to delete this edition?')) {
    try {
      await EditionService.deleteEdition(editionId.value);
      router.push({ name: 'Editions' });
    } catch (err) {
      error.value = 'Failed to delete edition: ' + err.message;
      console.error(err);
    }
  }
};

const updateEdition = async () => {
  try {
    const formData = new FormData();
    Object.keys(editionForm.value).forEach(key => {
      if (edition.value[key] !== editionForm.value[key]) {
        if (key === 'dossier_sponso' && editionForm.value.dossier_sponso instanceof File) {
          formData.append(key, editionForm.value.dossier_sponso);
        } else if (key !== 'dossier_sponso') {
          formData.append(key, editionForm.value[key]);
        }
      }
    });
    console.log('Updating edition with data:', Object.fromEntries(formData.entries()));
    await EditionService.updateEdition(editionId.value, formData);
    await fetchEditionDetails();
    showUpdateModal.value = false;
  } catch (err) {
    error.value = 'Failed to update edition: ' + err.message;
    console.error(err);
  }
};
</script>

<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css");

.conference-card {
  font-family: 'Segoe UI', sans-serif;
  margin-top: 80px;
  max-width: 1000px;
  margin-left: auto;
  margin-right: auto;
  width: 100%;
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

@media (max-width: 768px) {
  .conference-card {
    margin-top: 60px;
  }
  .card-body {
    flex-direction: column;
    padding: 1.5rem;
  }
  .action-buttons {
    flex-wrap: wrap;
  }
}

.loading,
.error-message {
  text-align: center;
  padding: 1rem;
}

.error-message {
  color: #e53935;
  background-color: #fee;
  border-radius: 4px;
}
</style>