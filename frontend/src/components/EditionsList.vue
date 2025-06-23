<template>
  <section class="editions-section">
    <!-- Header -->
    <div class="header">
      <h2>Éditions</h2>
      <button class="new-edition-btn" @click="showModal = true">
        <span>➕</span> Nouvelle Édition
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
    <div v-if="!isLoading && !error">
      <div class="edition-card" v-for="edition in editions" :key="edition.id">
        <img src="@/assets/logosite.png"  class="edition-image"/>
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
              <i class="icon">📍</i>
              <div>
                <strong>Lieu</strong>
                <p>{{ edition.place }}</p>
              </div>
            </div>
            <div class="detail">
              <i class="icon">🕒</i>
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
      <div class="modal">
        <h2>Nouvelle Édition</h2>
        <form @submit.prevent="addEdition">
          <input type="text" v-model="newEdition.name" placeholder="Nom" required />
          <textarea v-model="newEdition.description_fr" placeholder="Description (FR)" required></textarea>
          <textarea v-model="newEdition.description_en" placeholder="Description (EN)" required></textarea>
          <input type="date" v-model="newEdition.start_date" required />
          <input type="date" v-model="newEdition.end_date" required />
          <input type="text" v-model="newEdition.place" placeholder="Lieu" required />

          <!-- PDF upload field -->
          <div class="file-upload">
            <label for="sponsor-dossier">Dossier Sponsoring (PDF):</label>
            <input id="sponsor-dossier" type="file" accept=".pdf" @change="onPdfSelected" required />
            <p v-if="pdfFileName" class="file-name">{{ pdfFileName }}</p>
          </div>

          <div class="modal-actions">
            <button type="submit" class="add-btn">Ajouter</button>
            <button type="button" class="cancel-btn" @click="closeModal">Annuler</button>
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
const pdfFileName = ref(''); // Add this line to define pdfFileName

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
    
    // Append the PDF file with the correct field name
    if (newEdition.value.dossier_sponso) {
      formData.append('dossier_sponso', newEdition.value.dossier_sponso);
    }
    // const editionData = {
    //   name: newEdition.value.name,
    //   description_fr: newEdition.value.description_fr,
    //   description_en: newEdition.value.description_en,
    //   start_date: newEdition.value.start_date,
    //   end_date: newEdition.value.end_date,
    //   place: newEdition.value.place
    // };
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
  router.push({'name': 'AdEdition', params: { editionId } });
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
</script>

<style scoped>
.editions-section {
  font-family: 'Segoe UI', sans-serif;
  width: 100%;
  max-width: 1200px;
  margin: auto;
  padding: 1rem;
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
  color: #265985;
}

.new-edition-btn {
  border: 1px solid #000;
  background: white;
  padding: 0.5rem 1rem;
  border-radius: 999px;
  font-weight: 500;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.3s ease;
}

.new-edition-btn:hover {
  border-color: #265985;
  color: #265985;
  background-color: #fff;
  transform: scale(1.05);
}

.edition-card {
  display: flex;
  flex-wrap: wrap;
  align-items: flex-start;
  background: white;
  border-radius: 15px;
  margin-top: 1.5rem;
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

.edition-image.placeholder {
  background: #f5f5f5;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #666;
  font-size: 0.8rem;
}
.file-upload {
  margin-bottom: 1rem;
}

.file-upload label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: bold;
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

.detail strong {
  color: #3c3c9c;
  font-size: 0.9rem;
}

.icon {
  font-size: 1.3rem;
  color: #6a0dad;
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

/* === MODAL === */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 999;
}

.modal {
  background: white;
  padding: 2rem;
  border-radius: 15px;
  width: 90%;
  max-width: 500px;
  box-shadow: 0 9px 40px rgba(0, 0, 0, 0.1);
  animation: fadeInUp 0.4s ease;
}

.modal h2 {
  margin-bottom: 1rem;
  color: #265985;
}

.modal input,
.modal textarea {
  width: 100%;
  margin-bottom: 1rem;
  padding: 0.6rem;
  border: 1px solid #ccc;
  border-radius: 10px;
  font-size: 1rem;
}

.modal textarea {
  resize: vertical;
  height: 80px;
}

.modal-actions {
  display: flex;
  justify-content: space-between;
  gap: 1rem;
}

.add-btn {
  background: #265985;
  color: white;
  border: none;
  padding: 0.6rem 1rem;
  border-radius: 10px;
  cursor: pointer;
}

.cancel-btn {
  background: #ccc;
  color: #333;
  border: none;
  padding: 0.6rem 1rem;
  border-radius: 10px;
  cursor: pointer;
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
}
</style>