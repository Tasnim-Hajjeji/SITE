<template>
  <div class="container">
    <h1 class="title">Speakers {{ selectedEditionName }}</h1>

    <!-- Actions -->
    <div class="actions">
      <button class="btn add" @click="openAddModal">
        <span class="plus">+</span> Add speaker
      </button>

      <!-- Dropdown -->
      <div class="dropdown" @click="toggleDropdown">
        <button class="btn edit">Edition ▼</button>
        <ul v-if="dropdownOpen" class="dropdown-menu">
          <li v-for="edition in editions" :key="edition.id" @click="selectEdition(edition)">
            {{ edition.name }}
          </li>
        </ul>
      </div>
    </div>

    <!-- Grid Speakers -->
    <div class="grid">
      <div class="card" v-for="speaker in speakers" :key="speaker.id">
        <h2 class="name">
          {{ speaker.full_name }}
          <img class="flag" :src="`https://flagcdn.com/${speaker.code_pays.toLowerCase()}.svg`" :alt="speaker.code_pays" />
        </h2>
        <p class="desc"><strong>Profession :</strong> {{ speaker.profession_fr }}</p>
        <p class="desc"><strong>Intervention :</strong> {{ speaker.description_fr }}</p>
        <p class="info">📌 <span class="count">{{ speaker.programs ? speaker.programs.length : 0 }}</span> Intervention(s)</p>
        <div class="info">
          <img class="avatar" :src="getImageUrl(speaker.image_url)" :alt="speaker.full_name" />
          <span>{{ speaker.institut }}</span>
        </div>
        <div class="tools">
          <button class="icon-btn" @click="openEditModal(speaker)"><i class="fas fa-edit"></i></button>
          <button class="icon-btn" @click="openDeleteModal(speaker)"><i class="fas fa-trash"></i></button>
          <button class="icon-btn" @click="openProgramModal(speaker)"><i class="fas fa-calendar-plus"></i></button>
        </div>
      </div>
    </div>

    <!-- Modal Add -->
    <div v-if="showAddModal" class="modal-overlay">
      <div class="modal">
        <h3>Add Speaker</h3>
        <form @submit.prevent="addSpeaker">
          <input v-model="newSpeaker.full_name" placeholder="Full Name" required />
          <input v-model="newSpeaker.profession_fr" placeholder="Profession FR" required />
          <input v-model="newSpeaker.profession_en" placeholder="Profession EN" required />
          <textarea v-model="newSpeaker.description_fr" placeholder="Description FR"></textarea>
          <textarea v-model="newSpeaker.description_en" placeholder="Description EN"></textarea>
          <input v-model="newSpeaker.institut" placeholder="Institution" required />
          <input v-model="newSpeaker.code_pays" placeholder="Country Code (e.g., TN)" required />
          <input type="file" @change="handleImageUpload($event, newSpeaker)" required />
          <button type="submit">Submit</button>
          <button type="button" @click="showAddModal = false">Cancel</button>
        </form>
      </div>
    </div>

    <!-- Modal Edit -->
    <div v-if="showEditModal" class="modal-overlay">
      <div class="modal">
        <h3>Edit Speaker</h3>
        <form @submit.prevent="updateSpeaker">
          <input v-model="editSpeaker.full_name" required />
          <input v-model="editSpeaker.profession_fr" required />
          <input v-model="editSpeaker.profession_en" required />
          <textarea v-model="editSpeaker.description_fr"></textarea>
          <textarea v-model="editSpeaker.description_en"></textarea>
          <input v-model="editSpeaker.institut" required />
          <input v-model="editSpeaker.code_pays" required />
          <input type="file" @change="handleImageUpload($event, editSpeaker)" />
          <button type="submit">Update</button>
          <button type="button" @click="showEditModal = false">Cancel</button>
        </form>
      </div>
    </div>

    <!-- Modal Delete -->
    <div v-if="showDeleteModal" class="modal-overlay">
      <div class="modal">
        <h3>Are you sure you want to delete "{{ deleteSpeakerData.full_name }}"?</h3>
        <button @click="deleteSpeaker">Yes, Delete</button>
        <button @click="showDeleteModal = false">Cancel</button>
      </div>
    </div>

    <!-- Modal Program -->
    <div v-if="showProgramModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-2xl shadow-lg max-h-[80vh] overflow-y-auto">
        <h3 class="text-xl font-bold text-gray-800 mb-4 text-center">Add/Remove Programmes for {{ selectedSpeaker.full_name }}</h3>
        <div class="space-y-4">
          <div v-for="(program, index) in programs" :key="index" class="p-4 border rounded-lg flex justify-between items-center" :class="{ 'border-2 border-blue-500': selectedPrograms.includes(program.id) }">
            <div>
              <p class="text-sm font-medium text-gray-700">{{ program.title }}</p>
              <p class="text-xs text-gray-500">Start: {{ program.startDate }} - End: {{ program.endDate }}</p>
              <p class="text-xs text-gray-500">{{ program.description }}</p>
            </div>
            <div class="flex space-x-2">
              <button @click="addProgram(program.id)" class="text-green-500 hover:text-green-700">
                <i class="fas fa-plus"></i>
              </button>
              <button @click="removeProgram(program.id)" class="text-red-500 hover:text-red-700">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
        </div>
        <div class="mt-6 flex justify-end space-x-4">
          <button @click="closeProgramModal" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400">Cancel</button>
          <button @click="confirmPrograms" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Confirm</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import IntervenantService from '@/services/IntervenantService';
import EditionService from '@/services/EditionService';

export default {
  name: "SpeakerAdmin",
  data() {
    return {
      dropdownOpen: false,
      editions: [],
      selectedEditionId: null,
      selectedEditionName: '',
      showAddModal: false,
      showEditModal: false,
      showDeleteModal: false,
      showProgramModal: false,
      selectedSpeaker: null,
      selectedPrograms: [],
      newSpeaker: {
        full_name: "",
        description_fr: "",
        description_en: "",
        profession_fr: "",
        profession_en: "",
        edition_id: null,
        image: null,
        institut: "",
        code_pays: "TN"
      },
      editSpeaker: null,
      deleteSpeakerData: null,
      speakers: [],
      programs: [
        { id: 1, title: "Keynote Address", startDate: "2025-06-25 09:00", endDate: "2025-06-25 10:00", description: "Opening speech by the chief guest" },
        { id: 2, title: "Panel Discussion", startDate: "2025-06-25 10:30", endDate: "2025-06-25 12:00", description: "Experts discussing future trends" },
        { id: 3, title: "Workshop Session", startDate: "2025-06-25 13:30", endDate: "2025-06-25 15:00", description: "Hands-on training on new technologies" },
        { id: 4, title: "Closing Ceremony", startDate: "2025-06-25 16:00", endDate: "2025-06-25 17:00", description: "Awards and closing remarks" },
      ],
    };
  },
  async created() {
    await this.fetchEditions();
    
    // Set initial edition from route params or localStorage
    const routeEditionId = this.$route.params.editionId;
    const storedEditionId = localStorage.getItem('selectedEditionId');
    
    this.selectedEditionId = routeEditionId || storedEditionId || (this.editions.length > 0 ? this.editions[0].id : null);
    
    if (this.selectedEditionId) {
      localStorage.setItem('selectedEditionId', this.selectedEditionId);
      await this.fetchSpeakers();
    }
  },
  methods: {
    async fetchEditions() {
      try {
        const response = await EditionService.getAllEditions();
        this.editions = response.data;
        
        if (this.editions.length > 0 && !this.selectedEditionId) {
          this.selectedEditionId = this.editions[0].id;
          this.selectedEditionName = this.editions[0].name;
        } else if (this.selectedEditionId) {
          const selectedEdition = this.editions.find(e => e.id == this.selectedEditionId);
          this.selectedEditionName = selectedEdition ? selectedEdition.name : '';
        }
      } catch (error) {
        console.error('Error fetching editions:', error);
      }
    },
    async fetchSpeakers() {
      try {
        const response = await IntervenantService.getIntervenantsByEdition(this.selectedEditionId);
        this.speakers = response.data;
      } catch (error) {
        console.error('Error fetching speakers:', error);
      }
    },
    toggleDropdown() {
      this.dropdownOpen = !this.dropdownOpen;
    },
    async selectEdition(edition) {
      this.selectedEditionId = edition.id;
      this.selectedEditionName = edition.name;
      this.dropdownOpen = false;
      localStorage.setItem('selectedEditionId', edition.id);
      await this.fetchSpeakers();
    },
    openAddModal() {
      this.newSpeaker = {
        full_name: "",
        description_fr: "",
        description_en: "",
        profession_fr: "",
        profession_en: "",
        edition_id: this.selectedEditionId,
        image: null,
        institut: "",
        code_pays: "TN"
      };
      this.showAddModal = true;
    },
    handleImageUpload(event, targetSpeaker) {
      const file = event.target.files[0];
      if (file) {
        targetSpeaker.image = file;
      }
    },
    async addSpeaker() {
      try {
        console.log('Adding speaker:', this.newSpeaker);
        const formData = new FormData();
        
        // Append all speaker data to formData
        Object.keys(this.newSpeaker).forEach(key => {
          if (key !== 'image' && this.newSpeaker[key] !== null) {
            formData.append(key, this.newSpeaker[key]);
          }
        });
        
        if (this.newSpeaker.image) {
          formData.append('photo', this.newSpeaker.image);
        }
        console.log('Form data:', formData);
        for (const [key, value] of formData.entries()) {
          console.log(`${key}: ${value}`);
        }
        await IntervenantService.createIntervenant(formData);
        this.showAddModal = false;
        await this.fetchSpeakers();
      } catch (error) {
        console.error('Error adding speaker:', error);
      }
    },
    getImageUrl(imagePath) {
      return `http://localhost:8000/storage/${imagePath}`;
    },
    openEditModal(speaker) {
      this.editSpeaker = { ...speaker };
      this.showEditModal = true;
    },
    async updateSpeaker() {
      try {
        const formData = new FormData();
        
        // Append all speaker data to formData
        Object.keys(this.editSpeaker).forEach(key => {
          if (key !== 'image' && this.editSpeaker[key] !== null && key !== 'image_url') {
            formData.append(key, this.editSpeaker[key]);
          }
        });
        
        if (this.editSpeaker.image) {
          formData.append('photo', this.editSpeaker.image);
        }

        await IntervenantService.updateIntervenant(this.editSpeaker.id, formData);
        this.showEditModal = false;
        await this.fetchSpeakers();
      } catch (error) {
        console.error('Error updating speaker:', error);
      }
    },
    openDeleteModal(speaker) {
      this.deleteSpeakerData = speaker;
      this.showDeleteModal = true;
    },
    async deleteSpeaker() {
      try {
        await IntervenantService.deleteIntervenant(this.deleteSpeakerData.id);
        this.showDeleteModal = false;
        await this.fetchSpeakers();
      } catch (error) {
        console.error('Error deleting speaker:', error);
      }
    },
    openProgramModal(speaker) {
      this.selectedSpeaker = speaker;
      this.selectedPrograms = speaker.programs ? speaker.programs.map(p => p.id) : [];
      this.showProgramModal = true;
    },
    addProgram(programId) {
      if (!this.selectedPrograms.includes(programId)) {
        this.selectedPrograms.push(programId);
      }
    },
    removeProgram(programId) {
      const index = this.selectedPrograms.indexOf(programId);
      if (index !== -1) {
        this.selectedPrograms.splice(index, 1);
      }
    },
    closeProgramModal() {
      this.showProgramModal = false;
      this.selectedSpeaker = null;
      this.selectedPrograms = [];
    },
    confirmPrograms() {
      // Placeholder for backend integration
      console.log('Confirmed programs for', this.selectedSpeaker.full_name, ':', this.selectedPrograms);
      this.closeProgramModal();
    },
  },
};
</script>

<style scoped>
/* Your existing styles remain the same */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.3);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal {
  background: white;
  border-radius: 12px;
  padding: 20px;
  width: 90%;
  max-width: 500px;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
}

.modal h3 {
  margin-bottom: 15px;
}

.modal form input,
.modal form textarea {
  width: 100%;
  margin-bottom: 10px;
  padding: 8px;
  font-size: 14px;
  border: 1px solid #ccc;
  border-radius: 8px;
}

.modal button {
  margin-right: 10px;
  padding: 8px 14px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
}

.modal button:first-of-type {
  background-color: #265985;
  color: white;
}

.modal button:last-of-type {
  background-color: #ccc;
}

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