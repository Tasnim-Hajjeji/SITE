<template>
  <section class="editions-section">
    <!-- Header -->
    <div class="header">
      <h2>Editions</h2>
      <button class="new-edition-btn" @click="showModal = true">
        <i class="fas fa-plus text-white"></i> Nouvelle Édition
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="isLoading" class="loading">
      Chargement en cours...
    </div>

    <!-- Error State -->
    <div v-if="error" class="error">
      Erreur de chargement: {{ error }}
    </div>

    <!-- Edition Cards -->
    <div v-if="!isLoading && !error" class="editions-container">
      <div class="edition-card" v-for="edition in editions" :key="edition.id">
        <img :src="getRandomConferenceImage()" class="edition-image" />
        <div class="edition-info">
          <div class="title-status">
            <h3>{{ edition.name }}</h3>
            <span class="status" :class="{
              'current': isCurrentEdition(edition),
              'past': !isCurrentEdition(edition)
            }">
              {{ getEditionStatus(edition) }}
            </span>
          </div>
          <div class="details">
            <div class="detail">
              <i class="fas fa-map-marker-alt"></i>
              <div>
                <strong>Lieu</strong>
                <p>{{ edition.place }}</p>
              </div>
            </div>
            <div class="detail">
              <i class="fas fa-calendar-alt"></i>
              <div>
                <strong>Date</strong>
                <p>{{ formatDateRange(edition.start_date, edition.end_date) }}</p>
              </div>
            </div>
          </div>
        </div>
        <button class="voir-plus" @click="selectEdition(edition.id)">
          Voir plus
        </button>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="modal-overlay">
      <div class="modal-content">
        <h3 class="text-xl font-bold text-blue-700 mb-4 ">Nouvelle Édition</h3>
        <form @submit.prevent="addEdition" class="space-y-0">
          <div>
            <label for="edition-name" class="block mb-1 text-xs text-gray-500 font-medium">Nom:</label>
            <input id="edition-name" type="text" v-model="newEdition.name" placeholder="Nom" required
              class="w-full p-2 border border-gray-300 rounded-lg" />
          </div>
          <div>
            <label for="description-fr" class="block mb-1 text-xs text-gray-500 font-medium">Description (FR):</label>
            <textarea id="description-fr" v-model="newEdition.description_fr" placeholder="Description (FR)" required
              class="w-full p-2 border border-gray-300 rounded-lg h-20 resize-y"></textarea>
          </div>
          <div>
            <label for="description-en" class="block mb-1 text-xs text-gray-500 font-medium">Description (EN):</label>
            <textarea id="description-en" v-model="newEdition.description_en" placeholder="Description (EN)" required
              class="w-full p-2 border border-gray-300 rounded-lg h-20 resize-y"></textarea>
          </div>
          <div>
            <label for="start-date" class="block mb-1 text-xs text-gray-500 font-medium">Date de début:</label>
            <input id="start-date" type="date" v-model="newEdition.start_date" required
              class="w-full p-2 border border-gray-300 rounded-lg" />
          </div>
          <div>
            <label for="end-date" class="block mb-1 text-xs text-gray-500 font-medium">Date de fin:</label>
            <input id="end-date" type="date" v-model="newEdition.end_date" required
              class="w-full p-2 border border-gray-300 rounded-lg" />
          </div>
          <div>
            <label for="place" class="block mb-1 text-xs text-gray-500 font-medium">Lieu:</label>
            <input id="place" type="text" v-model="newEdition.place" placeholder="Lieu" required
              class="w-full p-2 border border-gray-300 rounded-lg" />
          </div>
          <!-- PDF upload field -->
          <div class="file-upload">
            <label for="sponsor-dossier" class="block mb-1 text-xs text-gray-500 font-medium">Dossier Sponsoring (PDF):</label>
            <div class="relative w-[95%]">
              <input id="sponsor-dossier" type="file" accept=".pdf" @change="onPdfSelected" required
                class="w-full p-2 border border-gray-300 rounded-lg opacity-0 absolute z-10 cursor-pointer" />
              <div
                class="w-full p-2 border border-gray-300 rounded-lg bg-white flex items-center justify-between cursor-pointer">
                <span class="text-gray-600">Choisir un fichier</span>
                <i class="fas fa-download text-gray-600"></i>
              </div>
            </div>
            <p v-if="pdfFileName" class="mt-1 text-sm text-gray-600">{{ pdfFileName }}</p>
          </div>
          <div class="modal-actions flex justify-end gap-2 mt-6">
            <button type="submit" class="add-btn bg-gradient-to-r mt-4 from-blue-800 to-blue-600 text-white font-semibold rounded-lg px-4 py-1.5 hover:from-blue-900 hover:to-blue-700 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out">Ajouter</button>
            <button type="button" class="cancel-btn bg-gradient-to-r mt-4 from-gray-200 to-gray-300 text-gray-800 font-semibold rounded-lg px-4 py-1.5 hover:from-gray-300 hover:to-gray-400 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out" @click="closeModal">Annuler</button>
          </div>
        </form>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import EditionService from '@/services/EditionService';

const router = useRouter();
const showModal = ref(false);
const isLoading = ref(true);
const error = ref(null);
const editions = ref([]);
const pdfFileName = ref('');

const newEdition = ref({
  name: '',
  description_fr: '',
  description_en: '',
  start_date: '',
  end_date: '',
  place: '',
  dossier_sponso: null
});

// French month names
const frenchMonths = [
  'janvier', 'février', 'mars', 'avril', 'mai', 'juin',
  'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'
];

// Fetch editions on component mount
onMounted(async () => {
  await fetchEditions();
});

// Fetch editions from API
const fetchEditions = async () => {
  isLoading.value = true;
  error.value = null;
  try {
    const response = await EditionService.getAllEditions();
    editions.value = response.data;
  } catch (err) {
    error.value = err.message;
    console.error('Error fetching editions:', err);
  } finally {
    isLoading.value = false;
  }
};

// Check if edition is current (end date not reached)
const isCurrentEdition = (edition) => {
  const today = new Date();
  const endDate = new Date(edition.end_date);
  return endDate >= today;
};

// Get edition status text
const getEditionStatus = (edition) => {
  return isCurrentEdition(edition) ? 'En Cours' : 'Terminé';
};

// Format date range in French style
const formatDateRange = (startDate, endDate) => {
  const start = new Date(startDate);
  const end = new Date(endDate);
  
  // Same month and year
  if (start.getMonth() === end.getMonth() && start.getFullYear() === end.getFullYear()) {
    return `${start.getDate()} - ${end.getDate()} ${frenchMonths[start.getMonth()]} ${start.getFullYear()}`;
  }
  // Same year
  else if (start.getFullYear() === end.getFullYear()) {
    return `${start.getDate()} ${frenchMonths[start.getMonth()]} - ${end.getDate()} ${frenchMonths[end.getMonth()]} ${start.getFullYear()}`;
  }
  // Different years
  return `${start.getDate()} ${frenchMonths[start.getMonth()]} ${start.getFullYear()} - ${end.getDate()} ${frenchMonths[end.getMonth()]} ${end.getFullYear()}`;
};

// Handle PDF selection
const onPdfSelected = (event) => {
  const file = event.target.files[0];
  if (file) {
    if (file.type === 'application/pdf') {
      newEdition.value.dossier_sponso = file;
      pdfFileName.value = file.name;
    } else {
      error.value = 'Veuillez sélectionner un fichier PDF';
      event.target.value = ''; // Reset the input
      pdfFileName.value = '';
    }
  }
};

// Add new edition
const addEdition = async () => {
  try {
    const formData = new FormData();
    formData.append('name', newEdition.value.name);
    formData.append('description_fr', newEdition.value.description_fr);
    formData.append('description_en', newEdition.value.description_en);
    formData.append('start_date', newEdition.value.start_date);
    formData.append('end_date', newEdition.value.end_date);
    formData.append('place', newEdition.value.place);
    
    if (newEdition.value.dossier_sponso) {
      formData.append('dossier_sponso', newEdition.value.dossier_sponso);
    }
    console.log('Form Data:', formData);
    const response = await EditionService.createEdition(formData);
    editions.value.unshift(response.data);
    closeModal();
  } catch (err) {
    error.value = 'Erreur lors de la création: ' + err.message;
    console.error('Error creating edition:', err);
  }
};

const selectEdition = (editionId) => {
  localStorage.setItem('selectedEditionId', editionId);
  router.push({ name: 'AdEdition', params: { editionId } });
};

const closeModal = () => {
  showModal.value = false;
  pdfFileName.value = '';
  newEdition.value = {
    name: '',
    description_fr: '',
    description_en: '',
    start_date: '',
    end_date: '',
    place: '',
    dossier_sponso: null
  };
};

// Get random conference image
const getRandomConferenceImage = () => {
  const imageNumbers = [1, 2, 3, 4, 5, 6];
  const randomIndex = Math.floor(Math.random() * imageNumbers.length);
  return require(`@/assets/conference${imageNumbers[randomIndex]}.jpg`);
};
</script>

<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css");

.editions-section {
  font-family: 'Segoe UI', sans-serif;
  width: 100%;
  max-width: 1200px;
  margin: auto;
  padding: 1rem;
  margin-top: 2rem;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 1rem;
}

.header h2 {
  font-size: 1.7rem;
  font-weight: bold;
  color: #265985;
}

.new-edition-btn {
  border: 1px solid #265985;
  background: #265985;
  padding: 0.5rem 1rem;
  border-radius: 999px;
  font-weight: 500;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.3s ease;
  color: white;
}

.new-edition-btn:hover {
  background-color: #1e4b6b;
  transform: scale(1.05);
}

.editions-container {
  margin-top: 1.5rem;
}

.edition-card {
  display: flex;
  flex-wrap: wrap;
  align-items: flex-start;
  background: white;
  border-radius: 15px;
  padding: 1rem;
  box-shadow: 0 9px 40px rgba(0, 0, 0, 0.05);
  gap: 1rem;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  animation: fadeInUp 0.6s ease;
}

.edition-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 50px rgba(0, 0, 0, 0.1);
}

.loading,
.error {
  text-align: center;
  padding: 2rem;
  font-size: 1.2rem;
}

.error {
  color: #ff4444;
}

.status {
  border-radius: 999px;
  padding: 0.2rem 0.8rem;
  font-size: 0.8rem;
}

.status.current {
  background-color: #e8f5e9;
  color: #2e7d32;
  border: 1px solid #2e7d32;
}

.status.past {
  background-color: #ffebee;
  color: #c62828;
  border: 1px solid #c62828;
}

.edition-image {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 10px;
  flex-shrink: 0;
  transition: transform 0.3s ease;
}

.edition-card:hover .edition-image {
  transform: rotate(2deg) scale(1.05);
}

.edition-info {
  flex: 1;
  min-width: 220px;
}

.title-status {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 1rem;
  margin-bottom: 0.5rem;
}

.title-status h3 {
  color: #265985;
  font-size: 1.2rem;
}

.status {
  border: 1px solid #265985;
  border-radius: 999px;
  padding: 0.2rem 0.8rem;
  font-size: 0.8rem;
  color: #265985;
}

.details {
  display: flex;
  flex-wrap: wrap;
  gap: 2rem;
}

.detail {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #666;
}

.detail i {
  color: #265985;
  font-size: 1.3rem;
}

.detail strong {
  color: #265985;
  font-size: 0.9rem;
}

.voir-plus {
  background: #265985;
  color: white;
  border: none;
  border-radius: 10px;
  padding: 0.7rem 1.2rem;
  font-weight: bold;
  cursor: pointer;
  margin-left: auto;
  margin-top: 1rem;
  transition: background 0.3s ease, transform 0.3s ease;
}

.voir-plus:hover {
  background: #1e4b6b;
  transform: translateY(-2px);
}

/* Modal Styles */
.modal-overlay {
  position: fixed;
  inset: 0;
  background-color: rgba(0, 0, 0, 0.45);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 999;
  
}
.modal-content h3 {
  font-size: 1.3rem;
  margin-bottom: 1rem;
  color: #1b2d56;
  text-align: center;
}

.modal-content {
  background: white;
  padding: 30px;
  border-radius: 10px;
  max-width: 500px;
  width: 100%;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
  animation: fadeInZoom 0.3s ease-out;
  max-height: 80vh;
  overflow-y: auto;
  -webkit-overflow-scrolling: touch;
}

@keyframes fadeInZoom {
  0% {
    opacity: 0;
    transform: scale(0.85);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}

.modal-content h3 {
  font-size: 1.3rem;
  margin-bottom: 1rem;
  color: #1b2d56;
}

.modal-content input,
.modal-content textarea {
  width: 95%;
  padding: 10px 14px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  margin-bottom: 12px;
  font-size: 14px;
}

.modal-content input:focus,
.modal-content textarea:focus {
  border-color: #265985;
  outline: none;
}

.modal-content label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: bold;
  color: #1f2937;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 10px;
}

.add-btn {
  background: linear-gradient(to right, #265985, #1e4b6b);
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  font-weight: 600;
  font-size: 0.9rem;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
}

.add-btn:hover {
  background: linear-gradient(to right, #1e4b6b, #163a52);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.cancel-btn {
  background: linear-gradient(to right, #d1d5db, #b0b7c3);
  color: #1f2937;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  font-weight: 600;
  font-size: 0.9rem;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
}

.cancel-btn:hover {
  background: linear-gradient(to right, #b0b7c3, #9ca3af);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.file-upload {
  margin-bottom: 1rem;
}

.file-upload input[type="file"] {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.file-name {
  margin-top: 0.5rem;
  font-size: 0.9rem;
  color: #666;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .edition-card {
    flex-direction: column;
    align-items: flex-start;
  }

  .voir-plus {
    width: 100%;
  }

  .details {
    flex-direction: column;
    gap: 1rem;
  }

  .header h2 {
    font-size: 1.5rem;
  }

  .new-edition-btn {
    width: 100%;
    justify-content: center;
  }

  .modal-content {
    max-height: 90vh;
    padding: 20px;
  }
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>