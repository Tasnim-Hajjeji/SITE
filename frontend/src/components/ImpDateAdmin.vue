<template>
  <div class="container">
    <div class="header-row">
      <h2 class="title">Dates importantes</h2>
      <div class="action-buttons">
        <button class="add-btn" @click="openAddModal">
          <i class="fas fa-plus"></i> Ajouter une date
        </button>
      </div>
    </div>

    <div v-if="isLoading" class="loading">
      Chargement des dates importantes...
    </div>

    <div v-else-if="error" class="error-message">
      {{ error }}
    </div>

    <div v-else>
      <div v-if="importantDates.length > 0" class="dates-list">
        <div v-for="date in importantDates" :key="date.id" class="date-card">
          <div class="card-content">
            <p class="date-highlight"><strong>Date:</strong> {{ formatDate(date.date) }}</p>
            <h3> Titre :   {{ date.title_en }} / {{ date.title_fr }}</h3>
            <p>{{ date.description_en }}</p>
            <p>{{ date.description_fr }}</p>
          </div>
          <div class="actions">
            <button class="small-btn update-btn" @click="editDate(date.id)"><i class="fas fa-pen"></i></button>
            <button class="small-btn delete-btn" @click="confirmDelete(date.id)"><i class="fas fa-trash"></i></button>
          </div>
        </div>
      </div>
      <p v-else class="empty-msg">Aucune date importante ajoutée pour l'instant.</p>
    </div>

    <!-- Add/Edit Modal -->
    <transition name="fade">
      <div v-if="showModal" class="modal-overlay">
        <div class="modal-content">
          <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">{{ isEditing ? 'Modifier la Date' : 'Ajouter une Date' }}</h3>
          <form @submit.prevent="saveDate" class="space-y-0">
            <div>
              <label for="title_en" class="block mb-1 text-xs text-gray-500 font-medium">Titre (Anglais):</label>
              <input v-model="form.title_en" id="title_en" type="text" placeholder="Entrez le titre en anglais"
                class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
            </div>
            <div>
              <label for="title_fr" class="block mb-1 text-xs text-gray-500 font-medium">Titre (Français):</label>
              <input v-model="form.title_fr" id="title_fr" type="text" placeholder="Entrez le titre en français"
                class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
            </div>
            <div>
              <label for="description_en" class="block mb-1 text-xs text-gray-500 font-medium">Description (Anglais):</label>
              <textarea v-model="form.description_en" id="description_en" placeholder="Entrez la description en anglais"
                class="w-[95%] p-2 border border-gray-300 rounded-lg h-20 resize-y" required></textarea>
            </div>
            <div>
              <label for="description_fr" class="block mb-1 text-xs text-gray-500 font-medium">Description (Français):</label>
              <textarea v-model="form.description_fr" id="description_fr" placeholder="Entrez la description en français"
                class="w-[95%] p-2 border border-gray-300 rounded-lg h-20 resize-y" required></textarea>
            </div>
            <div>
              <label for="date" class="block mb-1 text-xs text-gray-500 font-medium">Date:</label>
              <input v-model="form.date" id="date" type="date" class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
            </div>
            <div>
              <label for="edition_id" class="block mb-1 text-xs text-gray-500 font-medium">ID d'Édition:</label>
              <input v-model="form.edition_id" id="edition_id" type="number" placeholder="Entrez l'ID d'édition"
                class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
            </div>
            <div v-if="error" class="p-2 bg-red-100 text-red-700 rounded-md flex items-center">
              <span class="mr-1">!</span> {{ error }}
            </div>
            <div class="modal-actions flex justify-end gap-2 mt-6">
              <button type="button"
                class="cancel-btn bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 font-semibold rounded-lg px-4 py-1.5 hover:from-gray-300 hover:to-gray-400 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out"
                @click="closeModal">
                Annuler
              </button>
              <button type="submit"
                class="add-btn bg-gradient-to-r from-blue-800 to-blue-600 text-white font-semibold rounded-lg px-4 py-1.5 hover:from-blue-900 hover:to-blue-700 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out">
                {{ isEditing ? 'Enregistrer' : 'Ajouter' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </transition>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="modal-overlay">
      <div class="modal-content">
        <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Confirmer la suppression</h3>
        <p class="text-gray-600 mb-4">Êtes-vous sûr de vouloir supprimer la date "{{ form.title_en }}" ? Cette action est irréversible.</p>
        <div class="modal-actions flex justify-end gap-2 mt-6">
          <button type="button"
            class="cancel-btn bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 font-semibold rounded-lg px-4 py-1.5 hover:from-gray-300 hover:to-gray-400 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out"
            @click="showDeleteModal = false; editingId.value = null;">
            Annuler
          </button>
          <button type="button"
            class="delete-btn bg-gradient-to-r from-red-600 to-red-500 text-white font-semibold rounded-lg px-4 py-1.5 hover:from-red-700 hover:to-red-600 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out"
            @click="deleteDate(editingId.value)">
            Supprimer
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import ImportantDatesService from '@/services/ImportantDatesService';
import dayjs from 'dayjs';

export default {
  name: "ImportantDates",
  setup() {
    const route = useRoute();
    const importantDates = ref([]);
    const showModal = ref(false);
    const showDeleteModal = ref(false);
    const isEditing = ref(false);
    const editingId = ref(null);
    const isLoading = ref(true);
    const error = ref(null);

    const form = ref({
      title_fr: "",
      title_en: "",
      description_fr: "",
      description_en: "",
      date: "",
      edition_id: localStorage.getItem('selectedEditionId') || route.params.editionId 
    });

    // Fetch important dates
    const fetchImportantDates = async () => {
      try {
        isLoading.value = true;
        const editionId = form.value.edition_id;
        const response = await ImportantDatesService.getDatesByEdition(editionId);
        importantDates.value = response.data;

        // Sort dates chronologically
        importantDates.value.sort((a, b) => {
          return new Date(a.date) - new Date(b.date);
        });
      } catch (err) {
        error.value = 'Échec du chargement des dates importantes : ' + err.message;
        console.error(err);
      } finally {
        isLoading.value = false;
      }
    };

    // Open add modal
    const openAddModal = () => {
      resetForm();
      isEditing.value = false;
      showModal.value = true;
    };

    // Open edit modal
    const editDate = (id) => {
      const dateToEdit = importantDates.value.find(date => date.id === id);
      if (dateToEdit) {
        form.value = { ...dateToEdit };
        editingId.value = id;
        isEditing.value = true;
        showModal.value = true;
      }
    };

    // Save date (create or update)
    const saveDate = async () => {
      try {
        if (isEditing.value) {
          await ImportantDatesService.updateDate(editingId.value, form.value);
        } else {
          await ImportantDatesService.createDate(form.value);
        }
        await fetchImportantDates();
        closeModal();
      } catch (err) {
        error.value = `Échec de ${isEditing.value ? 'la modification' : 'l\'ajout'} de la date : ${err.message}`;
        console.error(err);
      }
    };

    // Confirm delete
    const confirmDelete = (id) => {
      const dateToDelete = importantDates.value.find(date => date.id === id);
      if (dateToDelete) {
        form.value.title_en = dateToDelete.title_en;
        editingId.value = id;
        showDeleteModal.value = true;
      }
    };

    // Delete date
    const deleteDate = async (id) => {
      try {
        await ImportantDatesService.deleteDate(id);
        await fetchImportantDates();
        showDeleteModal.value = false;
        editingId.value = null;
      } catch (err) {
        error.value = 'Échec de la suppression de la date : ' + err.message;
        console.error(err);
      }
    };

    // Close modal
    const closeModal = () => {
      showModal.value = false;
    };

    // Reset form
    const resetForm = () => {
      form.value = {
        title_fr: "",
        title_en: "",
        description_fr: "",
        description_en: "",
        date: "",
        edition_id: route.params.editionId || localStorage.getItem('selectedEditionId')
      };
    };

    // Format date for display
    const formatDate = (dateString) => {
      return dayjs(dateString).format('DD/MM/YYYY');
    };

    // Initialize
    onMounted(fetchImportantDates);

    return {
      importantDates,
      showModal,
      showDeleteModal,
      isEditing,
      editingId,
      form,
      isLoading,
      error,
      openAddModal,
      editDate,
      deleteDate,
      saveDate,
      closeModal,
      confirmDelete,
      formatDate
    };
  }
};
</script>

<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css");

.container {
  padding: 2rem;
  margin: auto;
  font-family: 'Segoe UI', sans-serif;
  animation: fadeInUp 0.6s ease;
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

/* Empty Message */
.empty-msg {
  text-align: center;
  color: #666;
  margin-top: 2rem;
  font-style: italic;
}

/* Buttons */
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

/* Card Styles */
.dates-list {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.date-card {
  border: 1px solid #e0e7ff;
  border-radius: 12px;
  padding: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

.date-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.card-content {
  flex: 1;
}

.date-highlight {
  font-size: 1.2rem;
  font-weight: 600;
  color: #265985;
  margin-bottom: 0.75rem;
  background: #e6f0fa;
  padding: 8px 12px;
  border-radius: 6px;
  display: inline-block;
}

.card-content h3 {
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: #1b2d56;
  font-size: 1.1rem;
}

.card-content p {
  margin-bottom: 4px;
  font-size: 14px;
  color: #444;
}

/* Actions side by side */
.actions {
  display: flex;
  gap: 0.5rem;
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

/* Fade transition */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
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

/* Responsive */
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

  .date-card {
    flex-direction: column;
    gap: 1rem;
  }

  .actions {
    justify-content: center;
  }

  .modal-content {
    padding: 20px;
  }

  .modal-actions {
    flex-direction: column;
    align-items: stretch;
  }

  .btn {
    width: 100%;
    text-align: center;
  }
}
</style>