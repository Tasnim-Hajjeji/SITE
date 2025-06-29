```vue
<template>
  <div class="container">
    <h1 class="title">Intervenants {{ selectedEditionName }}</h1>

    <!-- Actions -->
    <div class="actions">
      <button class="btn add" @click="openAddModal">
        <span class="plus">+</span> Ajouter un Intervenant
      </button>

      <!-- Dropdown -->
      <div class="dropdown" @click="toggleDropdown">
        <button class="btn edit">Édition ▼</button>
        <ul v-if="dropdownOpen" class="dropdown-menu">
          <li v-for="edition in editions" :key="edition.id" @click="selectEdition(edition)">
            {{ edition.name }}
          </li>
        </ul>
      </div>
    </div>

    <!-- Grid Speakers -->
    <div class="grid">
      <div class="card" v-for="speaker in speakers" :key="speaker.id" :id="`speaker-${speaker.id}`">
        <h2 class="name">
          {{ speaker.full_name }}
          <img class="flag" :src="`https://flagcdn.com/${speaker.code_pays.toLowerCase()}.svg`" :alt="speaker.code_pays" />
        </h2>
        <p class="desc"><strong>Profession :</strong> {{ speaker.profession_fr }}</p>
        <p class="desc"><strong>Intervention :</strong> {{ speaker.description_fr }}</p>
        <p class="info"><i class="fas fa-map-pin icon"></i> <span class="count">{{ speaker.programs ? speaker.programs.length : 0 }}</span> Intervention(s)</p>
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
      <div class="modal-content">
        <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Ajouter un Intervenant</h3>
        <form @submit.prevent="addSpeaker" class="space-y-0">
          <div>
            <label for="full_name_input" class="block mb-1 text-xs text-gray-500 font-medium">Nom complet</label>
            <input id="full_name_input" v-model="newSpeaker.full_name" placeholder="Nom complet" required class="w-[95%] p-2 border border-gray-300 rounded-lg" />
          </div>
          <div>
            <label for="profession_fr_input" class="block mb-1 text-xs text-gray-500 font-medium">Profession (FR)</label>
            <input id="profession_fr_input" v-model="newSpeaker.profession_fr" placeholder="Profession FR" required class="w-[95%] p-2 border border-gray-300 rounded-lg" />
          </div>
          <div>
            <label for="profession_en_input" class="block mb-1 text-xs text-gray-500 font-medium">Profession (EN)</label>
            <input id="profession_en_input" v-model="newSpeaker.profession_en" placeholder="Profession EN" required class="w-[95%] p-2 border border-gray-300 rounded-lg" />
          </div>
          <div>
            <label for="description_fr_input" class="block mb-1 text-xs text-gray-500 font-medium">Description (FR)</label>
            <textarea id="description_fr_input" v-model="newSpeaker.description_fr" placeholder="Description FR" class="w-[95%] p-2 border border-gray-300 rounded-lg"></textarea>
          </div>
          <div>
            <label for="description_en_input" class="block mb-1 text-xs text-gray-500 font-medium">Description (EN)</label>
            <textarea id="description_en_input" v-model="newSpeaker.description_en" placeholder="Description EN" class="w-[95%] p-2 border border-gray-300 rounded-lg"></textarea>
          </div>
          <div>
            <label for="institut_input" class="block mb-1 text-xs text-gray-500 font-medium">Institution</label>
            <input id="institut_input" v-model="newSpeaker.institut" placeholder="Institution" required class="w-[95%] p-2 border border-gray-300 rounded-lg" />
          </div>
          <div>
            <label for="code_pays_input" class="block mb-1 text-xs text-gray-500 font-medium">Code du pays</label>
            <select id="code_pays_input" v-model="newSpeaker.code_pays" required class="w-[95%] p-2 border border-gray-300 rounded-lg">
              <option value="TN">TN (Tunisia)</option>
              <option value="FR">FR (France)</option>
              <option value="US">US (United States)</option>
              <option value="GB">GB (United Kingdom)</option>
              <option value="DE">DE (Germany)</option>
              <option value="IT">IT (Italy)</option>
              <option value="ES">ES (Spain)</option>
              <option value="CA">CA (Canada)</option>
              <option value="AU">AU (Australia)</option>
              <option value="JP">JP (Japan)</option>
            </select>
          </div>
          <div>
            <label for="image_input" class="block mb-1 text-xs text-gray-500 font-medium">Image</label>
            <button type="button" id="image_input" @click="$refs.fileInput.click()" class="w-[95%] p-2 border border-gray-300 rounded-lg flex items-center justify-center text-gray-600 hover:text-gray-800">
              <i class="fas fa-upload"></i> Télécharger une image
            </button>
            <input ref="fileInput" type="file" @change="handleImageUpload($event, newSpeaker)" class="hidden" required />
          </div>
          <div class="modal-actions flex justify-end gap-2 mt-6">
            <button type="button" class="cancel-btn mt-4 bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 font-semibold rounded-lg px-4 py-1.5 hover:from-gray-300 hover:to-gray-400 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out" @click="showAddModal = false">
              Annuler
            </button>
            <button type="submit" class="add-btn mt-4 bg-gradient-to-r from-blue-800 to-blue-600 text-white font-semibold rounded-lg px-4 py-1.5 hover:from-blue-900 hover:to-blue-700 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out">
              Soumettre
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal Edit -->
    <div v-if="showEditModal" class="modal-overlay">
      <div class="modal-content">
        <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Mettre à jour l'Orateur</h3>
        <form @submit.prevent="updateSpeaker" class="space-y-0">
          <div>
            <label for="edit_full_name_input" class="block mb-1 text-xs text-gray-500 font-medium">Nom complet</label>
            <input id="edit_full_name_input" v-model="editSpeaker.full_name" required class="w-[95%] p-2 border border-gray-300 rounded-lg" />
          </div>
          <div>
            <label for="edit_profession_fr_input" class="block mb-1 text-xs text-gray-500 font-medium">Profession (FR)</label>
            <input id="edit_profession_fr_input" v-model="editSpeaker.profession_fr" required class="w-[95%] p-2 border border-gray-300 rounded-lg" />
          </div>
          <div>
            <label for="edit_profession_en_input" class="block mb-1 text-xs text-gray-500 font-medium">Profession (EN)</label>
            <input id="edit_profession_en_input" v-model="editSpeaker.profession_en" required class="w-[95%] p-2 border border-gray-300 rounded-lg" />
          </div>
          <div>
            <label for="edit_description_fr_input" class="block mb-1 text-xs text-gray-500 font-medium">Description (FR)</label>
            <textarea id="edit_description_fr_input" v-model="editSpeaker.description_fr" class="w-[95%] p-2 border border-gray-300 rounded-lg"></textarea>
          </div>
          <div>
            <label for="edit_description_en_input" class="block mb-1 text-xs text-gray-500 font-medium">Description (EN)</label>
            <textarea id="edit_description_en_input" v-model="editSpeaker.description_en" class="w-[95%] p-2 border border-gray-300 rounded-lg"></textarea>
          </div>
          <div>
            <label for="edit_institut_input" class="block mb-1 text-xs text-gray-500 font-medium">Institution</label>
            <input id="edit_institut_input" v-model="editSpeaker.institut" required class="w-[95%] p-2 border border-gray-300 rounded-lg" />
          </div>
          <div>
            <label for="edit_code_pays_input" class="block mb-1 text-xs text-gray-500 font-medium">Code du pays</label>
            <select id="edit_code_pays_input" v-model="editSpeaker.code_pays" required class="w-[95%] p-2 border border-gray-300 rounded-lg">
              <option value="TN">TN (Tunisia)</option>
              <option value="FR">FR (France)</option>
              <option value="US">US (United States)</option>
              <option value="GB">GB (United Kingdom)</option>
              <option value="DE">DE (Germany)</option>
              <option value="IT">IT (Italy)</option>
              <option value="ES">ES (Spain)</option>
              <option value="CA">CA (Canada)</option>
              <option value="AU">AU (Australia)</option>
              <option value="JP">JP (Japan)</option>
            </select>
          </div>
          <div>
            <label for="edit_image_input" class="block mb-1 text-xs text-gray-500 font-medium">Image</label>
            <button type="button" id="edit_image_input" @click="$refs.editFileInput.click()" class="w-[95%] p-2 border border-gray-300 rounded-lg flex items-center justify-center text-gray-600 hover:text-gray-800">
              <i class="fas fa-upload"></i> Télécharger une image
            </button>
            <input ref="editFileInput" type="file" @change="handleImageUpload($event, editSpeaker)" class="hidden" />
          </div>
          <div class="modal-actions flex justify-end gap-2 mt-6">
            <button type="button" class="cancel-btn mt-4 bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 font-semibold rounded-lg px-4 py-1.5 hover:from-gray-300 hover:to-gray-400 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out" @click="showEditModal = false">
              Annuler
            </button>
            <button type="submit" class="add-btn mt-4 bg-gradient-to-r from-blue-800 to-blue-600 text-white font-semibold rounded-lg px-4 py-1.5 hover:from-blue-900 hover:to-blue-700 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out">
              Mettre à jour
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal Delete -->
    <div v-if="showDeleteModal" class="modal-overlay">
      <div class="modal-content">
        <h3 class="text-xl font-bold mb-4 text-center">Supprimer un Orateur</h3>
        <p class="text-center mb-4">Êtes-vous sûr de vouloir supprimer "<strong>{{ deleteSpeakerData.full_name }}</strong>" ?</p>
        <div class="modal-actions flex justify-end gap-2">
          <button type="button" class="cancel-btn bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 font-semibold rounded-lg px-4 py-1.5 hover:from-gray-300 hover:to-gray-400 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out" @click="showDeleteModal = false">
            Annuler
          </button>
          <button type="button" class="delete-btn bg-gradient-to-r from-red-600 to-red-800 text-white font-semibold rounded-lg px-4 py-1.5 hover:from-red-700 hover:to-red-900 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out" @click="deleteSpeaker">
            Oui, Supprimer
          </button>
        </div>
      </div>
    </div>

    <!-- Modal Program -->
    <div v-if="showProgramModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-2xl shadow-lg max-h-[80vh] overflow-y-auto">
        <h3 class="text-xl font-bold text-gray-800 mb-4 text-center">
          Ajouter/Supprimer des Programmes pour {{ selectedSpeaker.full_name }}
        </h3>

        <div class="flex justify-end mb-4">
          <button @click="selectedPrograms = []" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600" :disabled="isUpdatingPrograms || selectedPrograms.length === 0">
            Supprimer tous
          </button>
        </div>

        <div v-if="isLoadingPrograms" class="text-center py-4">
          Chargement des programmes...
        </div>

        <div v-else class="space-y-4">
          <div v-for="(program, index) in programs" :key="index" class="p-4 border rounded-lg flex justify-between items-center" :class="{ 'border-2 border-blue-500': selectedPrograms.includes(program.id) }">
            <div>
              <p class="text-sm font-medium text-gray-700">{{ program.name_fr }}</p>
              <p class="text-xs text-gray-500">Début : {{ program.time_start }} - Fin : {{ program.time_end }}</p>
              <p class="text-xs text-gray-500">{{ program.description_fr }}</p>
            </div>
            <div class="flex space-x-2">
              <button @click="addProgram(program.id)" class="text-green-500 hover:text-green-700" :disabled="isUpdatingPrograms">
                <i class="fas fa-plus"></i>
              </button>
              <button @click="removeProgram(program.id)" class="text-red-500 hover:text-red-700" :disabled="isUpdatingPrograms">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>

          <div v-if="programs.length === 0" class="text-center py-4 text-gray-500">
            Aucun programme trouvé pour cette édition.
          </div>
        </div>

        <div class="mt-6 flex justify-end space-x-4">
          <button @click="closeProgramModal" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400" :disabled="isUpdatingPrograms">
            Annuler
          </button>
          <button @click="confirmPrograms" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600" :disabled="isUpdatingPrograms">
            <span v-if="isUpdatingPrograms">Enregistrement...</span>
            <span v-else>Confirmer</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import IntervenantService from '@/services/IntervenantService';
import EditionService from '@/services/EditionService';
import ProgramService from '@/services/ProgramService';

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
      programs: [],
      isLoadingPrograms: false,
      isUpdatingPrograms: false,
    };
  },
  async created() {
    // Set initial edition from route params or localStorage
    const storedEditionId = localStorage.getItem('selectedEditionId');
    this.selectedEditionId = storedEditionId;
    await this.fetchEditions();

    if (this.selectedEditionId) {
      await this.fetchSpeakers();
    }
  },
  watch: {
    '$route.query': {
      immediate: true,
      handler(query) {
        if (query.highlightType === 'speaker' && query.highlight) {
          this.highlightSpeaker(parseInt(query.highlight, 10));
        }
      }
    }
  },
  methods: {
    async fetchEditions() {
      try {
        const response = await EditionService.getAllEditions();
        this.editions = response.data;

        if (this.selectedEditionId) {
          const selectedEdition = this.editions.find(e => e.id == this.selectedEditionId);
          this.selectedEditionName = selectedEdition ? selectedEdition.name : '';
        }
      } catch (error) {
        console.error('Erreur lors de la récupération des éditions :', error);
      }
    },
    async fetchSpeakers() {
      try {
        const response = await IntervenantService.getIntervenantsByEdition(this.selectedEditionId);
        this.speakers = response.data;
        console.log('Orateurs récupérés :', this.speakers);
      } catch (error) {
        console.error('Erreur lors de la récupération des orateurs :', error);
      }
    },
    toggleDropdown() {
      this.dropdownOpen = !this.dropdownOpen;
    },
    async selectEdition(edition) {
      this.selectedEditionId = edition.id;
      this.selectedEditionName = edition.name;
      this.dropdownOpen = false;
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
        console.log('Ajout d\'un orateur :', this.newSpeaker);
        const formData = new FormData();

        Object.keys(this.newSpeaker).forEach(key => {
          if (key !== 'image' && this.newSpeaker[key] !== null) {
            formData.append(key, this.newSpeaker[key]);
          }
        });

        if (this.newSpeaker.image) {
          formData.append('photo', this.newSpeaker.image);
        }
        console.log('Données du formulaire :', formData);
        for (const [key, value] of formData.entries()) {
          console.log(`${key}: ${value}`);
        }
        await IntervenantService.createIntervenant(formData);
        this.showAddModal = false;
        await this.fetchSpeakers();
      } catch (error) {
        console.error('Erreur lors de l\'ajout de l\'orateur :', error);
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
        console.error('Erreur lors de la mise à jour de l\'orateur :', error);
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
        console.error('Erreur lors de la suppression de l\'orateur :', error);
      }
    },
    async openProgramModal(speaker) {
      this.selectedSpeaker = speaker;
      this.selectedPrograms = speaker.programs ? speaker.programs.map(p => p.id) : [];
      this.isLoadingPrograms = true;

      try {
        const response = await ProgramService.getProgramsByEdition(this.selectedEditionId);
        this.programs = response.data;
        this.showProgramModal = true;
      } catch (error) {
        console.error('Erreur lors de la récupération des programmes :', error);
      } finally {
        this.isLoadingPrograms = false;
      }
    },
    addProgram(programId) {
      if (!this.selectedPrograms.includes(programId)) {
        this.selectedPrograms.push(programId);
      }
    },
    removeProgram(programId) {
      this.selectedPrograms = this.selectedPrograms.filter(id => id !== programId);
    },
    closeProgramModal() {
      this.showProgramModal = false;
      this.selectedSpeaker = null;
      this.selectedPrograms = [];
    },
    async confirmPrograms() {
      if (!this.selectedSpeaker) return;

      this.isUpdatingPrograms = true;

      try {
        const formData = new FormData();

        this.selectedPrograms.forEach((id, index) => {
          formData.append(`program_ids[${index}]`, id);
        });

        if (this.selectedPrograms.length === 0) {
          formData.append('program_ids', '');
        }

        await IntervenantService.updateIntervenant(this.selectedSpeaker.id, formData);
        await this.fetchSpeakers();
        this.closeProgramModal();
      } catch (error) {
        console.error('Erreur lors de la mise à jour des programmes de l\'orateur :', error);
      } finally {
        this.isUpdatingPrograms = false;
      }
    },

    highlightSpeaker(speakerId) {
      if (this.speakers.length > 0) {
        this.scrollToSpeaker(speakerId);
        return;
      }

      const checkInterval = setInterval(() => {
        if (this.speakers.length > 0) {
          clearInterval(checkInterval);
          this.scrollToSpeaker(speakerId);
        }
      }, 100);
    },

    scrollToSpeaker(speakerId) {
      this.$nextTick(() => {
        const element = document.getElementById(`speaker-${speakerId}`);
        if (element) {
          element.scrollIntoView({ behavior: 'smooth', block: 'center' });
          element.classList.add('highlighted');
          setTimeout(() => {
            element.classList.remove('highlighted');
          }, 3000);
        }
      });
    },
  }
};
</script>

<style scoped>
.highlighted {
  animation: highlight 3s ease;
  box-shadow: 0 0 0 3px rgba(38, 89, 133, 0.5);
  transform: translateY(-2px);
}

@keyframes highlight {
  0% {
    box-shadow: 0 0 0 4px rgba(38, 89, 133, 0.8);
  }

  70% {
    box-shadow: 0 0 0 4px rgba(38, 89, 133, 0.8);
  }

  100% {
    box-shadow: none;
    transform: translateY(0);
  }
}

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
.modal-overlay h3 {
  color: #265985;
  font-weight: bold;
  margin-bottom: 20px;
}

.modal-content {
  background: white;
  border-radius: 12px;
  padding: 30px;
  width: 90%;
  max-width: 500px;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
  max-height: 80vh;
  overflow-y: auto;
  -webkit-overflow-scrolling: touch;
}

.modal-content h3 {
  margin-bottom: 15px;
  text-align: center;
}

.modal-content input,
.modal-content textarea,
.modal-content select {
  width: 95%;
  margin-bottom: 10px;
  padding: 8px 14px;
  font-size: 14px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
}

.modal-content input:focus,
.modal-content textarea:focus,
.modal-content select:focus {
  border-color: #265985;
  outline: none;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
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
  background: linear-gradient(to right, #e53935, #b71c1c);
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
  background: linear-gradient(to right, #d32f2f, #9a0007);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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

.icon {
  color: #265985;
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
```