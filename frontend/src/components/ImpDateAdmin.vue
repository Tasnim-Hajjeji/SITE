```vue
<template>
  <div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">📅 Important Dates</h2>

    <button class="btn add bg-white border border-teal-500 text-teal-500 px-4 py-2 rounded-full hover:bg-teal-500 hover:text-white transition-colors duration-200 mb-6" @click="openAddModal">➕ Add Date</button>

    <div v-if="importantDates.length > 0" class="dates-list space-y-4">
      <div v-for="(date, index) in importantDates" :key="index" class="date-card bg-gray-50 border-l-4 border-teal-500 rounded-lg p-5 flex justify-between items-center shadow-sm hover:shadow-md transition-transform duration-200 hover:scale-[1.01]">
        <div class="card-content">
          <h3 class="text-lg font-semibold text-gray-800">{{ date.title_en }} / {{ date.title_fr }}</h3>
          <p class="text-sm text-gray-600">{{ date.description_en }}</p>
          <p class="text-sm text-gray-600">{{ date.description_fr }}</p>
          <p class="text-sm text-gray-600"><strong>Date:</strong> {{ date.date }}</p>
          <p class="text-sm text-gray-600"><strong>Edition ID:</strong> {{ date.edition_id }}</p>
        </div>
        
        <div class="actions flex space-x-2">
          <button class="small-btn update-btn" @click="editDate(index)"><i class="fas fa-pen"></i></button>
          <button class="small-btn delete-btn" @click="openDeleteModal(index)"><i class="fas fa-trash"></i></button>
        </div>
      </div>
    </div>
    <p v-else class="empty-msg text-center text-gray-500 italic mt-8">No important dates added yet.</p>

    <!-- Add/Edit Modal -->
    <transition name="fade">
      <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
        <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-md font-poppins max-h-[90vh] overflow-y-auto">
          <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">{{ isEditing ? 'Edit Date' : 'Add Date' }}</h3>
          <form @submit.prevent="saveDate" class="space-y-4">
            <div>
              <label for="title_en" class="block text-sm font-medium text-gray-700">Title (EN)</label>
              <input
                v-model="form.title_en"
                id="title_en"
                type="text"
                placeholder="Enter title in English"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required
              />
            </div>
            <div>
              <label for="title_fr" class="block text-sm font-medium text-gray-700">Title (FR)</label>
              <input
                v-model="form.title_fr"
                id="title_fr"
                type="text"
                placeholder="Enter title in French"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required
              />
            </div>
            <div>
              <label for="description_en" class="block text-sm font-medium text-gray-700">Description (EN)</label>
              <textarea
                v-model="form.description_en"
                id="description_en"
                placeholder="Enter description in English"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                rows="4"
                required
              ></textarea>
            </div>
            <div>
              <label for="description_fr" class="block text-sm font-medium text-gray-700">Description (FR)</label>
              <textarea
                v-model="form.description_fr"
                id="description_fr"
                placeholder="Enter description in French"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                rows="4"
                required
              ></textarea>
            </div>
            <div>
              <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
              <input
                v-model="form.date"
                id="date"
                type="date"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required
              />
            </div>
            <div>
              <label for="edition_id" class="block text-sm font-medium text-gray-700">Edition ID</label>
              <input
                v-model="form.edition_id"
                id="edition_id"
                type="number"
                placeholder="Enter edition ID"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required
              />
            </div>
            <div class="flex justify-end space-x-3 mt-6">
              <button
                type="button"
                class="btn cancel"
                @click="closeModal"
              >
                Cancel
              </button>
              <button
                type="submit"
                class="btn add"
              >
                {{ isEditing ? 'Update' : 'Add' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </transition>

    <!-- Delete Confirmation Modal -->
    <transition name="fade">
      <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
        <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-md font-poppins max-h-[90vh] overflow-y-auto">
          <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">Delete Important Date</h3>
          <p class="text-gray-600 mb-6 text-center">Are you sure you want to delete <strong>{{ importantDates[deleteIndex]?.title_en }}</strong>?</p>
          <div class="flex justify-end space-x-3">
            <button type="button" class="btn cancel" @click="closeDeleteModal">Cancel</button>
            <button type="button" class="btn delete" @click="confirmDelete">Yes, Delete</button>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  name: "ImportantDates",
  data() {
    return {
      importantDates: [
        {
          title_en: "Abstract Submission Deadline",
          title_fr: "Date limite de soumission des résumés",
          description_en: "Last day to submit abstracts for the conference.",
          description_fr: "Dernier jour pour soumettre les résumés pour la conférence.",
          date: "2025-08-15",
          edition_id: 2025
        },
        {
          title_en: "Registration Opens",
          title_fr: "Ouverture des inscriptions",
          description_en: "Registration period starts for all participants.",
          description_fr: "Début de la période d'inscription pour tous les participants.",
          date: "2025-06-01",
          edition_id: 2025
        },
        {
          title_en: "Conference Date",
          title_fr: "Date de la conférence",
          description_en: "Main event for SITE 2024.",
          description_fr: "Événement principal pour SITE 2024.",
          date: "2024-10-10",
          edition_id: 2024
        },
        {
          title_en: "Early Bird Registration",
          title_fr: "Inscription anticipée",
          description_en: "Discounted registration period ends.",
          description_fr: "Fin de la période d'inscription à tarif réduit.",
          date: "2023-07-31",
          edition_id: 2023
        }
      ],
      showModal: false,
      isEditing: false,
      editingIndex: null,
      showDeleteModal: false,
      deleteIndex: null,
      form: {
        title_fr: "",
        title_en: "",
        description_fr: "",
        description_en: "",
        date: "",
        edition_id: null
      }
    };
  },
  created() {
    // Simulate checking if database is empty; populate with fictional data if empty
    if (this.importantDates.length === 0) {
      this.importantDates = [
        {
          title_en: "Abstract Submission Deadline",
          title_fr: "Date limite de soumission des résumés",
          description_en: "Last day to submit abstracts for the conference.",
          description_fr: "Dernier jour pour soumettre les résumés pour la conférence.",
          date: "2025-08-15",
          edition_id: 2025
        },
        {
          title_en: "Registration Opens",
          title_fr: "Ouverture des inscriptions",
          description_en: "Registration period starts for all participants.",
          description_fr: "Début de la période d'inscription pour tous les participants.",
          date: "2025-06-01",
          edition_id: 2025
        },
        {
          title_en: "Conference Date",
          title_fr: "Date de la conférence",
          description_en: "Main event for SITE 2024.",
          description_fr: "Événement principal pour SITE 2024.",
          date: "2024-10-10",
          edition_id: 2024
        },
        {
          title_en: "Early Bird Registration",
          title_fr: "Inscription anticipée",
          description_en: "Discounted registration period ends.",
          description_fr: "Fin de la période d'inscription à tarif réduit.",
          date: "2023-07-31",
          edition_id: 2023
        }
      ];
    }
  },
  methods: {
    openAddModal() {
      this.resetForm();
      this.showModal = true;
      this.isEditing = false;
    },
    editDate(index) {
      this.form = { ...this.importantDates[index] };
      this.editingIndex = index;
      this.isEditing = true;
      this.showModal = true;
    },
    openDeleteModal(index) {
      this.deleteIndex = index;
      this.showDeleteModal = true;
    },
    closeDeleteModal() {
      this.showDeleteModal = false;
      this.deleteIndex = null;
    },
    confirmDelete() {
      if (this.deleteIndex !== null) {
        this.importantDates.splice(this.deleteIndex, 1);
        this.closeDeleteModal();
      }
    },
    saveDate() {
      if (this.isEditing) {
        this.importantDates.splice(this.editingIndex, 1, { ...this.form });
      } else {
        this.importantDates.push({ ...this.form });
      }
      this.closeModal();
    },
    closeModal() {
      this.showModal = false;
    },
    resetForm() {
      this.form = {
        title_fr: "",
        title_en: "",
        description_fr: "",
        description_en: "",
        date: "",
        edition_id: null
      };
    }
  }
};
</script>

<style scoped>
/* Button styles for Add/Edit modal */
.btn.add {
  background-color: #265985;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 5px;
  border: none;
}
.btn.add:hover {
  background-color: #1e476b;
}
.btn.cancel {
  background: #999;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 5px;
  border: none;
}

/* Button styles for Delete modal */
.btn.delete {
  background: #eb5a5a;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 5px;
  border: none;
}

/* Update and Delete button styles from Programme.vue */
.small-btn {
  padding: 4px 8px;
  font-size: 0.8rem;
  border-radius: 6px;
  border: 1px solid;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
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

/* Fade transition styles */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

/* Poppins font */
.font-poppins {
  font-family: 'Poppins', sans-serif;
}
</style>
```