```vue
<template>
  <div class="container">
    <h1 class="title">Speakers 2024</h1>

    <!-- Actions -->
    <div class="actions">
      <button class="btn add" @click="openAddModal">
        <span class="plus">+</span> Add speaker
      </button>

      <!-- Dropdown -->
      <div class="dropdown" @click="toggleDropdown">
        <button class="btn edit">Edition ▼</button>
        <ul v-if="dropdownOpen" class="dropdown-menu">
          <li @click="selectEdition('SITE 2025')">SITE 2025</li>
          <li @click="selectEdition('SITE 2024')">SITE 2024</li>
          <li @click="selectEdition('SITE 2023')">SITE 2023</li>
          <li @click="selectEdition('SITE 2022')">SITE 2022</li>
        </ul>
      </div>
    </div>

    <!-- Grid Speakers -->
    <div class="grid">
      <div class="card" v-for="speaker in filteredSpeakers" :key="speaker.id">
        <h2 class="name">
          {{ speaker.full_name }}
          <img class="flag" src="https://flagcdn.com/tn.svg" alt="TN" />
        </h2>
        <p class="desc"><strong>Profession :</strong> {{ speaker.profession_fr }}</p>
        <p class="desc"><strong>Intervention :</strong> {{ speaker.description_fr }}</p>
        <p class="info">📌 <span class="count">1</span> Intervention</p>
        <div class="info">
          <img class="avatar" :src="speaker.image_preview" alt="avatar" />
          <span>{{ speaker.edition_id }}</span>
        </div>
        <div class="tools">
          <button class="icon-btn" @click="openEditModal(speaker)"><i class="fas fa-edit"></i></button>
          <button class="icon-btn" @click="openDeleteModal(speaker)"><i class="fas fa-trash"></i></button>
        </div>
      </div>
    </div>

    <!-- Add Modal -->
    <transition name="fade">
      <div v-if="showAddModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
        <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-md font-poppins max-h-[90vh] overflow-y-auto">
          <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">Add Speaker</h3>
          <form @submit.prevent="addSpeaker" class="space-y-4">
            <div>
              <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
              <input
                v-model="newSpeaker.full_name"
                id="full_name"
                type="text"
                placeholder="Enter full name"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required
              />
            </div>
            <div>
              <label for="profession_fr" class="block text-sm font-medium text-gray-700">Profession (FR)</label>
              <input
                v-model="newSpeaker.profession_fr"
                id="profession_fr"
                type="text"
                placeholder="Enter profession in French"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              />
            </div>
            <div>
              <label for="profession_en" class="block text-sm font-medium text-gray-700">Profession (EN)</label>
              <input
                v-model="newSpeaker.profession_en"
                id="profession_en"
                type="text"
                placeholder="Enter profession in English"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              />
            </div>
            <div>
              <label for="description_fr" class="block text-sm font-medium text-gray-700">Description (FR)</label>
              <textarea
                v-model="newSpeaker.description_fr"
                id="description_fr"
                placeholder="Enter description in French"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                rows="4"
              ></textarea>
            </div>
            <div>
              <label for="description_en" class="block text-sm font-medium text-gray-700">Description (EN)</label>
              <textarea
                v-model="newSpeaker.description_en"
                id="description_en"
                placeholder="Enter description in English"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                rows="4"
              ></textarea>
            </div>
            <div>
              <label for="edition_id" class="block text-sm font-medium text-gray-700">Edition</label>
              <input
                v-model="newSpeaker.edition_id"
                id="edition_id"
                type="text"
                placeholder="Enter edition (e.g., SITE 2025)"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              />
            </div>
            <div>
              <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
              <input
                id="image"
                type="file"
                @change="handleImageUpload($event, newSpeaker)"
                accept="image/*"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              />
            </div>
            <div class="flex justify-end space-x-3 mt-6">
              <button
                type="button"
                class="btn cancel"
                @click="showAddModal = false"
              >
                Cancel
              </button>
              <button
                type="submit"
                class="btn add"
              >
                Submit
              </button>
            </div>
          </form>
        </div>
      </div>
    </transition>

    <!-- Edit Modal -->
    <transition name="fade">
      <div v-if="showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
        <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-md font-poppins max-h-[90vh] overflow-y-auto">
          <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">Edit Speaker</h3>
          <form @submit.prevent="updateSpeaker" class="space-y-4">
            <div>
              <label for="edit_full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
              <input
                v-model="editSpeaker.full_name"
                id="edit_full_name"
                type="text"
                placeholder="Enter full name"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required
              />
            </div>
            <div>
              <label for="edit_profession_fr" class="block text-sm font-medium text-gray-700">Profession (FR)</label>
              <input
                v-model="editSpeaker.profession_fr"
                id="edit_profession_fr"
                type="text"
                placeholder="Enter profession in French"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              />
            </div>
            <div>
              <label for="edit_profession_en" class="block text-sm font-medium text-gray-700">Profession (EN)</label>
              <input
                v-model="editSpeaker.profession_en"
                id="edit_profession_en"
                type="text"
                placeholder="Enter profession in English"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              />
            </div>
            <div>
              <label for="edit_description_fr" class="block text-sm font-medium text-gray-700">Description (FR)</label>
              <textarea
                v-model="editSpeaker.description_fr"
                id="edit_description_fr"
                placeholder="Enter description in French"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                rows="4"
              ></textarea>
            </div>
            <div>
              <label for="edit_description_en" class="block text-sm font-medium text-gray-700">Description (EN)</label>
              <textarea
                v-model="editSpeaker.description_en"
                id="edit_description_en"
                placeholder="Enter description in English"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                rows="4"
              ></textarea>
            </div>
            <div>
              <label for="edit_edition_id" class="block text-sm font-medium text-gray-700">Edition</label>
              <input
                v-model="editSpeaker.edition_id"
                id="edit_edition_id"
                type="text"
                placeholder="Enter edition (e.g., SITE 2025)"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              />
            </div>
            <div>
              <label for="edit_image" class="block text-sm font-medium text-gray-700">Image</label>
              <input
                id="edit_image"
                type="file"
                @change="handleImageUpload($event, editSpeaker)"
                accept="image/*"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              />
            </div>
            <div class="flex justify-end space-x-3 mt-6">
              <button
                type="button"
                class="btn cancel"
                @click="showEditModal = false"
              >
                Cancel
              </button>
              <button
                type="submit"
                class="btn add"
              >
                Update
              </button>
            </div>
          </form>
        </div>
      </div>
    </transition>

    <!-- Delete Modal -->
    <transition name="fade">
      <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
        <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-md font-poppins max-h-[90vh] overflow-y-auto">
          <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">Delete Speaker</h3>
          <p class="text-gray-600 mb-6 text-center">Are you sure you want to delete <strong>{{ deleteSpeakerData.full_name }}</strong>?</p>
          <div class="flex justify-end space-x-3">
            <button
              type="button"
              class="btn cancel"
              @click="showDeleteModal = false"
            >
              Cancel
            </button>
            <button
              type="button"
              class="btn delete"
              @click="deleteSpeaker"
            >
              Yes, Delete
            </button>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  name: "SpeakersComponent",
  data() {
    return {
      dropdownOpen: false,
      selectedEdition: "SITE 2025",
      showAddModal: false,
      showEditModal: false,
      showDeleteModal: false,
      newSpeaker: {
        id: null,
        full_name: "",
        description_fr: "",
        description_en: "",
        profession_fr: "",
        profession_en: "",
        edition_id: "",
        image: null,
        image_preview: "",
      },
      editSpeaker: null,
      deleteSpeakerData: null,
      speakers: [],
    };
  },
  computed: {
    filteredSpeakers() {
      return this.speakers.filter((s) => s.edition_id === this.selectedEdition);
    },
  },
  methods: {
    toggleDropdown() {
      this.dropdownOpen = !this.dropdownOpen;
    },
    selectEdition(edition) {
      this.selectedEdition = edition;
      this.dropdownOpen = false;
    },
    openAddModal() {
      this.newSpeaker = {
        id: null,
        full_name: "",
        description_fr: "",
        description_en: "",
        profession_fr: "",
        profession_en: "",
        edition_id: this.selectedEdition,
        image: null,
        image_preview: "",
      };
      this.showAddModal = true;
    },
    handleImageUpload(event, targetSpeaker) {
      const file = event.target.files[0];
      if (file) {
        targetSpeaker.image = file;
        targetSpeaker.image_preview = URL.createObjectURL(file);
      }
    },
    addSpeaker() {
      const speaker = { ...this.newSpeaker, id: Date.now() };
      this.speakers.push(speaker);
      this.showAddModal = false;
    },
    openEditModal(speaker) {
      this.editSpeaker = { ...speaker };
      this.showEditModal = true;
    },
    updateSpeaker() {
      const index = this.speakers.findIndex((s) => s.id === this.editSpeaker.id);
      if (index !== -1) {
        this.speakers.splice(index, 1, { ...this.editSpeaker });
      }
      this.showEditModal = false;
    },
    openDeleteModal(speaker) {
      this.deleteSpeakerData = speaker;
      this.showDeleteModal = true;
    },
    deleteSpeaker() {
      this.speakers = this.speakers.filter((s) => s.id !== this.deleteSpeakerData.id);
      this.showDeleteModal = false;
    },
  },
};
</script>

<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css");

.container {
  padding: 24px;
  font-family: "Segoe UI", sans-serif;
}

.title {
  font-size: 24px;
  font-weight: bold;
  color: #2c3e50;
  margin-bottom: 20px;
}

.actions {
  display: flex;
  justify-content: space-between;
  margin-bottom: 20px;
  flex-wrap: wrap;
  gap: 10px;
}

.btn {
  padding: 10px 20px;
  border-radius: 9999px;
  font-weight: 500;
  cursor: pointer;
  font-size: 14px;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.add {
  background-color: #265985;
  color: white;
  border: none;
}

.add:hover {
  background-color: #1e476b;
  transform: scale(1.05);
}

.edit {
  border: 1px solid #265985;
  color: #265985;
  background: white;
}

.cancel {
  background: #999;
  color: white;
  border: none;
  padding: 0.3rem 1rem;
  border-radius: 5px;
}

.delete {
  background: #eb5a5a;
  color: white;
  border: none;
  padding: 0.3rem 1rem;
  border-radius: 5px;
}

.dropdown {
  position: relative;
}

.dropdown-menu {
  position: absolute;
  top: 100%;
  right: 0;
  margin-top: 5px;
  background: white;
  border: 1px solid #ccc;
  border-radius: 8px;
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
  z-index: 100;
  list-style: none;
  padding: 0;
  min-width: 160px;
  overflow: hidden;
}

.dropdown-menu li {
  padding: 10px 15px;
  cursor: pointer;
  transition: background 0.2s;
  font-size: 14px;
}

.dropdown-menu li:hover {
  background-color: #f0f4ff;
}

.grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 20px;
}

.card {
  background: #fefcfc;
  border-radius: 16px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  padding: 16px;
  border: 2px solid #265985;
  position: relative;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
}

.name {
  font-weight: 600;
  color: #1f2937;
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 10px;
}

.flag {
  width: 20px;
}

.desc {
  font-size: 14px;
  color: #4b5563;
  margin-bottom: 8px;
}

.info {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  color: #374151;
  margin-bottom: 8px;
}

.avatar {
  width: 32px;
  height: 32px;
  border-radius: 50%;
}

.tools {
  position: absolute;
  top: 8px;
  right: 8px;
  display: flex;
  gap: 8px;
}

.icon-btn {
  background: transparent;
  border: none;
  color: #6b7280;
  cursor: pointer;
  font-size: 16px;
}

.icon-btn:hover {
  color: #852c26;
}

/* Fade transition styles */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

/* Ensure Poppins font is applied */
.font-poppins {
  font-family: 'Poppins', sans-serif;
}

@media (max-width: 480px) {
  .btn {
    width: 100%;
    justify-content: center;
  }

  .actions {
    flex-direction: column;
    align-items: stretch;
  }
}
</style>
```