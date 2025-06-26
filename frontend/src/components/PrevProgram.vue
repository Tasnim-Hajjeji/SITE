<template>
  <div class="programme-container">
    <div class="header-row">
      <h1 class="title">Programme</h1>
      <div class="action-buttons">
        <button class="add-btn" @click="showModal = true">
          <i class="fas fa-plus"></i> Ajouter
        </button>
      </div>
    </div>

    <!-- Loading state -->
    <div v-if="isLoading" class="loading">
      Chargement des données du programme...
    </div>

    <!-- Error message -->
    <div v-if="error" class="error-message">
      {{ error }}
    </div>

    <!-- Content when loaded -->
    <div v-if="!isLoading && !error">
      <!-- Date Selector -->
      <div class="date-selector">
        <button v-for="date in dates" :key="date" :class="['date-btn', { active: selectedDate === date }]"
          @click="selectDate(date)">
          {{ date }}
        </button>
      </div>

      <!-- Timeline -->
      <div class="timeline">
        <div v-if="filteredPrograms.length === 0" class="no-events">
          Aucun programme prévu pour cette date
        </div>

        <div class="event" v-for="program in filteredPrograms" :key="program.id">
          <div class="circle"></div>
          <div class="content">
            <div class="event-header">
              <div class="time">{{ formatTime(program.time_start) }} — {{ formatTime(program.time_end) }}</div>
              <div class="buttons">
                <button class="small-btn update-btn" @click="openUpdateModal(program)">
                  <i class="fas fa-pen"></i>
                </button>
                <button class="small-btn delete-btn" @click="confirmDelete(program.id)">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </div>
            <div class="label">{{ program.name_en }}</div>
            <div class="description">{{ program.description_en }}</div>
          </div>
        </div>
      </div>

      <!-- Add Program Modal -->
      <div v-if="showModal" class="modal-overlay">
        <div class="modal-content">
          <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Ajouter un Programme</h3>
          <form @submit.prevent="addProgram" class="space-y-0">
            <div>
              <label for="name_fr" class="block mb-1 text-xs text-gray-500 font-medium">Nom (Français):</label>
              <input v-model="newProgram.name_fr" id="name_fr" type="text" placeholder="Entrez le nom en français"
                class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
            </div>
            <div>
              <label for="name_en" class="block mb-1 text-xs text-gray-500 font-medium">Nom (Anglais):</label>
              <input v-model="newProgram.name_en" id="name_en" type="text" placeholder="Entrez le nom en anglais"
                class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
            </div>
            <div>
              <label for="description_fr" class="block mb-1 text-xs text-gray-500 font-medium">Description (Français):</label>
              <textarea v-model="newProgram.description_fr" id="description_fr" placeholder="Entrez la description en français"
                class="w-[95%] p-2 border border-gray-300 rounded-lg h-20 resize-y" required></textarea>
            </div>
            <div>
              <label for="description_en" class="block mb-1 text-xs text-gray-500 font-medium">Description (Anglais):</label>
              <textarea v-model="newProgram.description_en" id="description_en" placeholder="Entrez la description en anglais"
                class="w-[95%] p-2 border border-gray-300 rounded-lg h-20 resize-y" required></textarea>
            </div>
            <div>
              <label for="time_start" class="block mb-1 text-xs text-gray-500 font-medium">Heure de début:</label>
              <input v-model="newProgram.time_start" id="time_start" type="datetime-local"
                class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
            </div>
            <div>
              <label for="time_end" class="block mb-1 text-xs text-gray-500 font-medium">Heure de fin:</label>
              <input v-model="newProgram.time_end" id="time_end" type="datetime-local"
                class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
            </div>
            <div v-if="error" class="p-2 bg-red-100 text-red-700 rounded-md flex items-center">
              <span class="mr-1">!</span> {{ error }}
            </div>
            <div class="modal-actions flex justify-end gap-2 mt-6">
              <button type="button"
                class="cancel-btn bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 font-semibold rounded-lg px-4 py-1.5 hover:from-gray-300 hover:to-gray-400 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out"
                @click="showModal = false; resetNewProgramForm();">
                Annuler
              </button>
              <button type="submit"
                class="add-btn bg-gradient-to-r from-blue-800 to-blue-600 text-white font-semibold rounded-lg px-4 py-1.5 hover:from-blue-900 hover:to-blue-700 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out">
                Ajouter
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Update Program Modal -->
      <div v-if="showUpdateModal" class="modal-overlay">
        <div class="modal-content">
          <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Mettre à jour le Programme</h3>
          <form @submit.prevent="updateProgram" class="space-y-0">
            <div>
              <label for="update_name_fr" class="block mb-1 text-xs text-gray-500 font-medium">Nom (Français):</label>
              <input v-model="selectedProgram.name_fr" id="update_name_fr" type="text" placeholder="Entrez le nom en français"
                class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
            </div>
            <div>
              <label for="update_name_en" class="block mb-1 text-xs text-gray-500 font-medium">Nom (Anglais):</label>
              <input v-model="selectedProgram.name_en" id="update_name_en" type="text" placeholder="Entrez le nom en anglais"
                class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
            </div>
            <div>
              <label for="update_description_fr" class="block mb-1 text-xs text-gray-500 font-medium">Description (Français):</label>
              <textarea v-model="selectedProgram.description_fr" id="update_description_fr" placeholder="Entrez la description en français"
                class="w-[95%] p-2 border border-gray-300 rounded-lg h-20 resize-y" required></textarea>
            </div>
            <div>
              <label for="update_description_en" class="block mb-1 text-xs text-gray-500 font-medium">Description (Anglais):</label>
              <textarea v-model="selectedProgram.description_en" id="update_description_en" placeholder="Entrez la description en anglais"
                class="w-[95%] p-2 border border-gray-300 rounded-lg h-20 resize-y" required></textarea>
            </div>
            <div>
              <label for="update_time_start" class="block mb-1 text-xs text-gray-500 font-medium">Heure de début:</label>
              <input v-model="selectedProgram.time_start" id="update_time_start" type="datetime-local"
                class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
            </div>
            <div>
              <label for="update_time_end" class="block mb-1 text-xs text-gray-500 font-medium">Heure de fin:</label>
              <input v-model="selectedProgram.time_end" id="update_time_end" type="datetime-local"
                class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
            </div>
            <div v-if="error" class="p-2 bg-red-100 text-red-700 rounded-md flex items-center">
              <span class="mr-1">!</span> {{ error }}
            </div>
            <div class="modal-actions flex justify-end gap-2 mt-6">
              <button type="button"
                class="cancel-btn bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 font-semibold rounded-lg px-4 py-1.5 hover:from-gray-300 hover:to-gray-400 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out"
                @click="showUpdateModal = false">
                Annuler
              </button>
              <button type="submit"
                class="add-btn bg-gradient-to-r from-blue-800 to-blue-600 text-white font-semibold rounded-lg px-4 py-1.5 hover:from-blue-900 hover:to-blue-700 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out">
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
          <p class="text-gray-600 mb-4">Êtes-vous sûr de vouloir supprimer le programme "{{ selectedProgramName }}" ? Cette action est irréversible.</p>
          <div class="modal-actions flex justify-end gap-2 mt-6">
            <button type="button"
              class="cancel-btn bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 font-semibold rounded-lg px-4 py-1.5 hover:from-gray-300 hover:to-gray-400 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out"
              @click="showDeleteModal = false; selectedProgramId = null;">
              Annuler
            </button>
            <button type="button"
              class="delete-btn bg-gradient-to-r from-red-600 to-red-500 text-white font-semibold rounded-lg px-4 py-1.5 hover:from-red-700 hover:to-red-600 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out"
              @click="deleteProgram">
              Supprimer
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import ProgramService from '@/services/ProgramService';
import EditionService from '@/services/EditionService';
import dayjs from 'dayjs';

export default {
  setup() {
    const route = useRoute();
    const editionId = ref(route.params.editionId || localStorage.getItem('selectedEditionId'));
    const edition = ref(null);
    const dates = ref([]);
    const selectedDate = ref('');
    const programs = ref([]);
    const showModal = ref(false);
    const showUpdateModal = ref(false);
    const showDeleteModal = ref(false);
    const selectedProgramId = ref(null);
    const selectedProgramName = ref('');
    const error = ref(null);
    const isLoading = ref(true);

    // Form data
    const newProgram = ref({
      name_fr: '',
      name_en: '',
      description_fr: '',
      description_en: '',
      time_start: '',
      time_end: '',
      edition_id: editionId.value
    });

    const selectedProgram = ref({
      id: null,
      name_fr: '',
      name_en: '',
      description_fr: '',
      description_en: '',
      time_start: '',
      time_end: '',
      edition_id: editionId.value
    });

    // Fetch edition details and programs
    const fetchData = async () => {
      try {
        isLoading.value = true;

        // Get edition details
        const editionResponse = await EditionService.getEdition(editionId.value);
        edition.value = editionResponse.data;

        // Get programs for this edition
        const programsResponse = await ProgramService.getProgramsByEdition(editionId.value);
        programs.value = programsResponse.data;

        // Extract unique dates
        extractDates();

        // Select first date by default
        if (dates.value.length > 0) {
          selectedDate.value = dates.value[0];
        }
      } catch (err) {
        error.value = 'Échec du chargement des données : ' + err.message;
        console.error(err);
      } finally {
        isLoading.value = false;
      }
    };

    // Extract unique dates from programs
    const extractDates = () => {
      const uniqueDates = new Set();
      programs.value.forEach(program => {
        const date = dayjs(program.time_start).format('DD/MM/YYYY');
        uniqueDates.add(date);
      });

      // Convert Set to Array and sort dates
      dates.value = Array.from(uniqueDates).sort((a, b) => {
        const dateA = dayjs(a, 'DD/MM/YYYY').toDate();
        const dateB = dayjs(b, 'DD/MM/YYYY').toDate();
        return dateA - dateB;
      });
    };

    // Filter programs by selected date
    const filteredPrograms = computed(() => {
      if (!selectedDate.value) return [];
      return programs.value.filter(program => {
        const programDate = dayjs(program.time_start).format('DD/MM/YYYY');
        return programDate === selectedDate.value;
      }).sort((a, b) => new Date(a.time_start) - new Date(b.time_start));
    });

    // Format time for display
    const formatTime = (dateTime) => {
      return dayjs(dateTime).format('HH:mm');
    };

    // Select date
    const selectDate = (date) => {
      selectedDate.value = date;
    };

    // Add new program
    const addProgram = async () => {
      try {
        if (!newProgram.value.name_fr || !newProgram.value.name_en) {
          throw new Error('Les noms en français et en anglais sont requis');
        }

        const startDate = dayjs(newProgram.value.time_start);
        const endDate = dayjs(newProgram.value.time_end);

        if (!startDate.isValid() || !endDate.isValid()) {
          throw new Error('Veuillez entrer des heures de début et de fin valides');
        }

        if (endDate.isBefore(startDate)) {
          throw new Error('L\'heure de fin doit être postérieure à l\'heure de début');
        }

        newProgram.value.time_start = startDate.format('YYYY-MM-DD HH:mm');
        newProgram.value.time_end = endDate.format('YYYY-MM-DD HH:mm');

        const response = await ProgramService.createProgram(newProgram.value);
        programs.value.push(response.data);
        extractDates();
        resetNewProgramForm();
        showModal.value = false;
      } catch (err) {
        error.value = 'Échec de l\'ajout du programme : ' + (err.response?.data?.error || err.message || 'Erreur inconnue');
        console.error('Détails de l\'erreur:', err.response?.data || err);
      }
    };

    // Open update modal
    const openUpdateModal = (program) => {
      selectedProgram.value = {
        ...program,
        time_start: dayjs(program.time_start).format('YYYY-MM-DDTHH:mm'),
        time_end: dayjs(program.time_end).format('YYYY-MM-DDTHH:mm')
      };
      showUpdateModal.value = true;
    };

    // Update program
    const updateProgram = async () => {
      try {
        const startDate = dayjs(selectedProgram.value.time_start);
        const endDate = dayjs(selectedProgram.value.time_end);

        if (!startDate.isValid() || !endDate.isValid()) {
          throw new Error('Veuillez entrer des dates valides');
        }

        const dataToSend = {
          ...selectedProgram.value,
          time_start: startDate.format('YYYY-MM-DD HH:mm'),
          time_end: endDate.format('YYYY-MM-DD HH:mm')
        };

        const response = await ProgramService.updateProgram(
          selectedProgram.value.id,
          dataToSend
        );

        const index = programs.value.findIndex(p => p.id === selectedProgram.value.id);
        if (index !== -1) {
          programs.value[index] = response.data;
        }

        showUpdateModal.value = false;
      } catch (err) {
        error.value = 'Échec de la mise à jour du programme : ' + (err.response?.data?.error || err.message);
        console.error(err);
      }
    };

    // Confirm delete
    const confirmDelete = (id) => {
      const program = programs.value.find(p => p.id === id);
      selectedProgramId.value = id;
      selectedProgramName.value = program ? program.name_en : '';
      showDeleteModal.value = true;
    };

    // Delete program
    const deleteProgram = async () => {
      try {
        await ProgramService.deleteProgram(selectedProgramId.value);
        programs.value = programs.value.filter(p => p.id !== selectedProgramId.value);
        extractDates();
        showDeleteModal.value = false;
        selectedProgramId.value = null;
        selectedProgramName.value = '';
      } catch (err) {
        error.value = 'Échec de la suppression du programme : ' + err.message;
        console.error(err);
      }
    };

    // Reset new program form
    const resetNewProgramForm = () => {
      newProgram.value = {
        name_fr: '',
        name_en: '',
        description_fr: '',
        description_en: '',
        time_start: '',
        time_end: '',
        edition_id: editionId.value
      };
    };

    // Initialize
    onMounted(fetchData);

    return {
      dates,
      selectedDate,
      programs,
      filteredPrograms,
      showModal,
      showUpdateModal,
      showDeleteModal,
      newProgram,
      selectedProgram,
      selectedProgramId,
      selectedProgramName,
      error,
      isLoading,
      selectDate,
      addProgram,
      openUpdateModal,
      updateProgram,
      confirmDelete,
      deleteProgram,
      formatTime,
      resetNewProgramForm
    };
  }
};
</script>

<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css");

.programme-container {
  padding: 2rem;
  max-width: 1200px;
  margin: auto;
  font-family: 'Segoe UI', sans-serif;
  animation: fadeInUp 0.6s ease;
}

.loading {
  text-align: center;
  padding: 2rem;
  font-style: italic;
  color: #666;
}

.error-message {
  padding: 1rem;
  background-color: #ffebee;
  color: #c62828;
  border-radius: 4px;
  margin-bottom: 1rem;
}

.no-events {
  text-align: center;
  padding: 2rem;
  color: #666;
  font-style: italic;
}

@keyframes fadeInUp {
  0% {
    opacity: 0;
    transform: translateY(40px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

.header-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
  flex-wrap: wrap;
  gap: 1rem;
}

.title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1b2d56;
  margin: 0;
  position: relative;
}

.title::after {
  content: "";
  width: 100px;
  height: 4px;
  background: #265985;
  display: block;
  margin-top: 8px;
  border-radius: 2px;
}

/* Action Button */
.action-buttons {
  display: flex;
  gap: 1rem;
}

.action-buttons button {
  padding: 8px 16px;
  border-radius: 25px;
  border: 1px solid;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.3s ease;
}

.add-btn {
  border-color: #265985;
  color: #265985;
  background-color: white;
}

.add-btn:hover {
  background-color: #265985;
  color: white;
}

/* Date Selector */
.date-selector {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin: 2rem 0;
}

.date-btn {
  border: 2px solid #265985;
  padding: 0.3rem 1.2rem;
  border-radius: 20px;
  background: white;
  color: #1b2d56;
  font-weight: 600;
  font-size: 14px;
  transition: all 0.3s ease;
  cursor: pointer;
}

.date-btn.active {
  background: #265985;
  color: white;
  font-weight: 700;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Timeline */
.timeline {
  position: relative;
  padding-left: 20px;
  border-left: 2px solid #8ab1d3;
  margin-bottom: 2rem;
}

.event {
  display: flex;
  align-items: flex-start;
  margin-bottom: 2rem;
  position: relative;
}

.circle {
  width: 14px;
  height: 14px;
  background: white;
  border: 2px solid #8ab1d3;
  border-radius: 50%;
  position: absolute;
  left: -7px;
  top: 0.3rem;
}

.content {
  margin-left: 1.5rem;
  flex: 1;
}

.event-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.buttons {
  display: flex;
  gap: 0.5rem;
}

.small-btn {
  padding: 6px 10px;
  font-size: 0.9rem;
  border-radius: 6px;
  border: 1px solid;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
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

.delete-btn {
  border-color: #e53935;
  color: #e53935;
  background-color: #fff;
}

.delete-btn:hover {
  background-color: #e53935;
  color: #fff;
}

.time {
  font-size: 13px;
  font-weight: 400;
  color: #6b7280;
  margin-bottom: 0.3rem;
}

.label {
  font-weight: 700;
  font-size: 16px;
  color: #1b2d56;
  margin-bottom: 0.2rem;
}

.description {
  font-size: 14px;
  color: #1b2d56;
  line-height: 1.5;
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

/* Responsive Styles */
@media (max-width: 768px) {
  .header-row {
    flex-direction: column;
    align-items: flex-start;
  }

  .action-buttons {
    width: 100%;
  }

  .action-buttons button {
    width: 100%;
  }

  .date-selector {
    flex-direction: column;
    align-items: stretch;
  }

  .date-btn {
    width: 100%;
  }

  .event-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }

  .modal-content {
    max-height: 90vh;
    padding: 20px;
  }
}
</style>