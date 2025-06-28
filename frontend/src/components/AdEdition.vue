<template>
  <section class="conference-card" v-if="!isLoading && edition">
    <div class="card-header">
      <h1>{{ edition.name }}</h1>
      <div class="action-buttons">
        <button class="delete-btn" @click="confirmDelete">
          <i class="fas fa-trash"></i> Supprimer
        </button>

        <button class="update-btn" @click="showUpdateModal = true">
          <i class="fas fa-pen"></i> Mettre à jour
        </button>

      </div>
    </div>

    <div class="card-body">
      <div class="card-right">
        <h2>{{ edition.name.toUpperCase() }}</h2>
        <button class="status" :class="{ 'current': isCurrentEdition }">
          {{ editionStatus }}
        </button>
        <p class="description">
          {{ edition.description_fr || 'Aucune description disponible' }}
        </p>

        <div class="info-row">
          <div class="info-item">
            <i class="fas fa-map-marker-alt"></i>
            <span class="label">Lieu</span>
            <span class="value">{{ edition.place || 'Non spécifié' }}</span>
          </div>

          <div class="info-item">
            <i class="fas fa-clock"></i>
            <span class="label">Date</span>
            <span class="value">{{ formattedDateRange }}</span>
          </div>

          <div class="info-item">
            <i class="fas fa-chart-bar"></i>
            <span class="label">Participants</span>
            <span class="value">{{ participantCount }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Update Modal -->
    <div v-if="showUpdateModal" class="modal-overlay">
      <div class="modal-content">
        <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Mettre à jour l'Édition</h3>
        <form @submit.prevent="updateEdition" class="space-y-0">
          <div>
            <label for="update_name" class="block mb-1 text-xs text-gray-500 font-medium">Nom:</label>
            <input v-model="editionForm.name" id="update_name" type="text" placeholder="Entrez le nom de l'édition"
              class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
          </div>
          <div>
            <label for="update_description_fr" class="block mb-1 text-xs text-gray-500 font-medium">Description
              (FR):</label>
            <textarea v-model="editionForm.description_fr" id="update_description_fr"
              placeholder="Entrez la description en français"
              class="w-[95%] p-2 border border-gray-300 rounded-lg h-20 resize-y" required></textarea>
          </div>
          <div>
            <label for="update_description_en" class="block mb-1 text-xs text-gray-500 font-medium">Description
              (EN):</label>
            <textarea v-model="editionForm.description_en" id="update_description_en"
              placeholder="Entrez la description en anglais"
              class="w-[95%] p-2 border border-gray-300 rounded-lg h-20 resize-y" required></textarea>
          </div>
          <div>
            <label for="update_start_date" class="block mb-1 text-xs text-gray-500 font-medium">Date de début:</label>
            <input v-model="editionForm.start_date" id="update_start_date" type="date"
              class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
          </div>
          <div>
            <label for="update_end_date" class="block mb-1 text-xs text-gray-500 font-medium">Date de fin:</label>
            <input v-model="editionForm.end_date" id="update_end_date" type="date"
              class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
          </div>
          <div>
            <label for="update_place" class="block mb-1 text-xs text-gray-500 font-medium">Lieu:</label>
            <input v-model="editionForm.place" id="update_place" type="text" placeholder="Entrez le lieu"
              class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
          </div>
          <div class="file-upload">
            <label for="update_dossier_sponso" class="block mb-1 text-xs text-gray-500 font-medium">Dossier Sponsoring
              (PDF):</label>
            <div class="relative w-[95%]">
              <input id="update_dossier_sponso" type="file"
                @change="onPdfSelectedSponsor" accept=".pdf"
                class="w-full p-2 border border-gray-300 rounded-lg opacity-0 absolute z-10 cursor-pointer" />
              <div
                class="w-full p-2 border border-gray-300 rounded-lg bg-white flex items-center justify-between cursor-pointer">
                <span class="text-gray-600">Choisir un fichier</span>
                <i class="fas fa-download text-gray-600"></i>
              </div>
              <p class="mt-1 text-sm text-gray-600">{{ pdfFileNameSponsor }}</p>
            </div>
          </div>
          <div class="file-upload">
            <label for="update_dossier_sponso" class="block mb-1 text-xs text-gray-500 font-medium">Call For Paper
              (PDF):</label>
            <div class="relative w-[95%]">
              <input id="update_dossier_sponso" type="file"
                @change="onPdfSelectedCall" accept=".pdf"
                class="w-full p-2 border border-gray-300 rounded-lg opacity-0 absolute z-10 cursor-pointer" />
              <div
                class="w-full p-2 border border-gray-300 rounded-lg bg-white flex items-center justify-between cursor-pointer">
                <span class="text-gray-600">Choisir un fichier</span>
                <i class="fas fa-download text-gray-600"></i>
              </div>
              <p class="mt-1 text-sm text-gray-600">{{ pdfFileNameCall }}</p>
            </div>
          </div>
          <div v-if="error" class="p-2 bg-red-100 text-red-700 rounded-md flex items-center">
            <span class="mr-1">!</span> {{ error }}
          </div>
          <div class="modal-actions flex justify-end gap-2 mt-6">
            <button type="button"
              class="cancel-btn bg-gradient-to-r mt-4 from-gray-200 to-gray-300 text-gray-800 font-semibold rounded-lg px-4 py-1.5 hover:from-gray-300 hover:to-gray-400 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out"
              @click="showUpdateModal = false">
              Annuler
            </button>
            <button type="submit"
              class="add-btn bg-gradient-to-r mt-4 from-blue-800 to-blue-600 text-white font-semibold rounded-lg px-4 py-1.5 hover:from-blue-900 hover:to-blue-700 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out">
              Enregistrer
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="modal-overlay">
      <div class="modal-content">
        <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Confirmer la suppression</h3>
        <p class="text-gray-600 mb-4">Êtes-vous sûr de vouloir supprimer l'édition "{{ edition?.name }}" ? Cette action
          est irréversible.</p>
        <div class="modal-actions flex justify-end gap-2 mt-6">
          <button type="button"
            class="cancel-btn bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 font-semibold rounded-lg px-4 py-1.5 hover:from-gray-300 hover:to-gray-400 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out"
            @click="showDeleteModal = false">
            Annuler
          </button>
          <button type="button"
            class="delete-btn bg-gradient-to-r from-red-600 to-red-500 text-white font-semibold rounded-lg px-4 py-1.5 hover:from-red-700 hover:to-red-600 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out"
            @click="deleteEdition">
            Supprimer
          </button>
        </div>
      </div>
    </div>
  </section>

  <div v-if="isLoading" class="loading">
    Chargement des données de l'édition...
  </div>
  <div v-if="error" class="error-message">
    {{ error }}
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import EditionService from '@/services/EditionService';
import ParticipantService from '@/services/ParticipantService';

const route = useRoute();
const router = useRouter();

const editionId = ref(localStorage.getItem('selectedEditionId') || route.params.editionId);
const editions = ref([]);
const edition = ref(null);
const isLoading = ref(true);
const error = ref(null);
const showUpdateModal = ref(false);
const showDeleteModal = ref(false);
const participantCount = ref(0);
const pdfFileNameCall = ref('');
const pdfFileNameSponsor = ref('');



// Form for updates
const editionForm = ref({
  name: '',
  description_fr: '',
  description_en: '',
  start_date: '',
  end_date: '',
  place: '',
  dossier_sponso: null,
  call_for_paper:null,
});

// French month names for date formatting
const frenchMonths = [
  'janvier', 'février', 'mars', 'avril', 'mai', 'juin',
  'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'
];

// Fetch data when component mounts or editionId changes
onMounted(async () => {
  await fetchEditions();
  await fetchEditionDetails();
  await fetchParticipantCount();
});

const fetchParticipantCount = async () => {
  try {
    const response = await ParticipantService.getParticipantsByEdition(editionId.value);
    participantCount.value = response.data.length;
  } catch (err) {
    console.error('Erreur lors de la récupération du nombre de participants:', err);
    participantCount.value = 0;
  }
};

// Fetch all editions for dropdown
const fetchEditions = async () => {
  try {
    const response = await EditionService.getAllEditions();
    editions.value = response.data;
  } catch (err) {
    error.value = 'Échec du chargement des éditions : ' + err.message;
    console.error(err);
  }
};

// Fetch details of the current edition
const fetchEditionDetails = async () => {
  isLoading.value = true;
  error.value = null;
  try {
    const response = await EditionService.getEdition(editionId.value);
    edition.value = response.data;
    editionForm.value = { ...response.data };
    await fetchParticipantCount();
  } catch (err) {
    error.value = 'Échec du chargement des détails de l\'édition : ' + err.message;
    console.error(err);
  } finally {
    isLoading.value = false;
  }
};
const onPdfSelectedSponsor = (event) => {
  const file = event.target.files[0];
  if (file) {
    if (file.type === 'application/pdf') {
      editionForm.value.dossier_sponso = file;
      pdfFileNameSponsor.value = file.name;
    } else {
      error.value = 'Veuillez sélectionner un fichier PDF';
      event.target.value = ''; // Reset the input
      pdfFileNameSponsor.value = '';
    }
  }
};
const onPdfSelectedCall = (event) => {
  const file = event.target.files[0];
  if (file) {
    if (file.type === 'application/pdf') {
      editionForm.value.call_for_paper = file;
      pdfFileNameCall.value = file.name;
    } else {
      error.value = 'Veuillez sélectionner un fichier PDF';
      event.target.value = ''; // Reset the input
      pdfFileNameCall.value = '';
    }
  }
};

// Computed properties
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
  if (!edition.value?.start_date || !edition.value?.end_date) return 'Dates non spécifiées';

  const start = new Date(edition.value.start_date);
  const end = new Date(edition.value.end_date);

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
});



// Edition operations
const confirmDelete = () => {
  showDeleteModal.value = true;
};

const deleteEdition = async () => {
  try {
    await EditionService.deleteEdition(editionId.value);
    localStorage.removeItem('selectedEditionId')
    router.push("/admin");
    showDeleteModal.value = false;
  } catch (err) {
    error.value = 'Échec de la suppression de l\'édition : ' + err.message;
    console.error(err);
  }
};

const updateEdition = async () => {
  try {
    const formData = new FormData();
    Object.keys(editionForm.value).forEach(key => {
      if (edition.value[key] !== editionForm.value[key]) {
        formData.append(key, editionForm.value[key]);
      }
    });
    if (editionForm.value.dossier_sponso instanceof File) {
      formData.append('dossier_sponso', editionForm.value.dossier_sponso);
    }
    if (editionForm.value.call_for_paper instanceof File) {
      formData.append('callForPaper', editionForm.value.call_for_paper);
    }
    console.log('Mise à jour de l\'édition avec les données:', Object.fromEntries(formData.entries()));
    await EditionService.updateEdition(editionId.value, formData);
    await fetchEditionDetails();
    showUpdateModal.value = false;
  } catch (err) {
    error.value = 'Échec de la mise à jour de l\'édition : ' + err.message;
    console.error(err);
  }
};
</script>

<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css");

.conference-card {
  font-family: 'Segoe UI', sans-serif;
  max-width: 1130px;
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

.modal-overlay {
  position: fixed;
  inset: 0;
  background-color: rgba(0, 0, 0, 0.45);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 999;
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
  text-align: center;
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

.delete-btn {
  background: linear-gradient(to right, #e53935, #c62828);
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  font-weight: 600;
  font-size: 0.9rem;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
}

.delete-btn:hover {
  background: linear-gradient(to right, #c62828, #b71c1c);
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

/* Responsive */
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
</style>