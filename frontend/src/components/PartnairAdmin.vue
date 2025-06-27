<template>
  <div class="container">
    <h1 class="title">Partenaires {{ this.selectedEditionName }}</h1>

    <div class="actions">
      <button class="btn add" @click="showAddModal = true">
        <span class="plus">+</span> Ajouter un Partenaire
      </button>

      <div class="dropdown" @click="toggleDropdown">
        <button class="btn edit">Édition ▼</button>
        <ul v-if="dropdownOpen" class="dropdown-menu">
          <li v-for="edition in editions" :key="edition.id" @click="onEditOption(edition.name)">
            {{ edition.name }}
          </li>
        </ul>
      </div>
    </div>

    <div class="grid">
      <div v-for="partner in filteredPartners" :key="partner.id" class="card" :id="`partner-${partner.id}`">
        <img :src="getImageUrl(partner.image_url)" alt="Logo du partenaire" class="logo" />
        <h2 class="name">{{ partner.name }}</h2>
        <p class="desc">{{ partner.description }}</p>
        <div class="info"><i class="fas fa-phone icon"></i><span>{{ partner.phone }}</span></div>
        <div class="info"><i class="fas fa-envelope icon"></i><span>{{ partner.email }}</span></div>
        <div class="tools">
          <button class="icon-btn" @click="openUpdateModal(partner)"><i class="fas fa-edit"></i></button>
          <button class="icon-btn" @click="openDeleteModal(partner)"><i class="fas fa-trash"></i></button>
        </div>
      </div>
    </div>

    <!-- Modal Ajout -->
    <div v-if="showAddModal" class="modal-overlay">
      <div class="modal-content">
        <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Ajouter un Partenaire</h3>
        <form @submit.prevent="addPartner" class="space-y-0">
          <div>
            <label for="name_input" class="block mb-1 text-xs text-gray-500 font-medium">Nom</label>
            <input id="name_input" name="name" type="text" v-model="newPartner.name" placeholder="Nom" class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
          </div>
          <div>
            <label for="image_input" class="block mb-1 text-xs text-gray-500 font-medium">Logo</label>
            <input id="image_input" name="image_url" type="file" @change="handleImageUpload($event, 'new')" class="w-[95%] p-2 border border-gray-300 rounded-lg" />
          </div>
          <div>
            <label for="edition_input" class="block mb-1 text-xs text-gray-500 font-medium">Édition</label>
            <select id="edition_input" name="edition_id" v-model.number="newPartner.edition_id" class="w-[95%] p-2 border border-gray-300 rounded-lg" required>
              <option disabled value="">Sélectionner une édition</option>
              <option v-for="edition in editions" :key="edition.id" :value="edition.id">{{ edition.name }}</option>
            </select>
          </div>
          <div>
            <label for="desc_input" class="block mb-1 text-xs text-gray-500 font-medium">Description</label>
            <input id="desc_input" name="description" type="text" v-model="newPartner.description" placeholder="Description" class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
          </div>
          <div>
            <label for="phone_input" class="block mb-1 text-xs text-gray-500 font-medium">Numéro de téléphone</label>
            <input id="phone_input" name="phone" type="text" v-model="newPartner.phone" placeholder="Numéro de téléphone" class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
          </div>
          <div>
            <label for="email_input" class="block mb-1 text-xs text-gray-500 font-medium">Email</label>
            <input id="email_input" name="email" type="email" v-model="newPartner.email" placeholder="Email" class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
          </div>
          <div class="modal-actions flex justify-end gap-2 mt-6">
            <button type="button"
              class="cancel-btn bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 font-semibold rounded-lg px-4 py-1.5 hover:from-gray-300 hover:to-gray-400 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out"
              @click="showAddModal = false">
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

    <!-- Modal Mise à jour -->
    <div v-if="showUpdateModal" class="modal-overlay">
      <div class="modal-content">
        <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Mettre à jour le Partenaire</h3>
        <form @submit.prevent="updatePartner" class="space-y-0">
          <div>
            <label for="update_name_input" class="block mb-1 text-xs text-gray-500 font-medium">Nom</label>
            <input id="update_name_input" name="name" type="text" v-model="selectedPartner.name" placeholder="Nom" class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
          </div>
          <div>
            <label for="update_image_input" class="block mb-1 text-xs text-gray-500 font-medium">Logo</label>
            <input id="update_image_input" name="image_url" type="file" @change="handleImageUpload($event, 'update')" class="w-[95%] p-2 border border-gray-300 rounded-lg" />
          </div>
          <div>
            <label for="update_edition_input" class="block mb-1 text-xs text-gray-500 font-medium">Édition</label>
            <select id="update_edition_input" name="edition_id" v-model.number="selectedPartner.edition_id" class="w-[95%] p-2 border border-gray-300 rounded-lg" required>
              <option disabled value="">Sélectionner une édition</option>
              <option v-for="edition in editions" :key="edition.id" :value="edition.id">{{ edition.name }}</option>
            </select>
          </div>
          <div>
            <label for="update_desc_input" class="block mb-1 text-xs text-gray-500 font-medium">Description</label>
            <input id="update_desc_input" name="description" type="text" v-model="selectedPartner.description" placeholder="Description" class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
          </div>
          <div>
            <label for="update_phone_input" class="block mb-1 text-xs text-gray-500 font-medium">Numéro de téléphone</label>
            <input id="update_phone_input" name="phone" type="text" v-model="selectedPartner.phone" placeholder="Numéro de téléphone" class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
          </div>
          <div>
            <label for="update_email_input" class="block mb-1 text-xs text-gray-500 font-medium">Email</label>
            <input id="update_email_input" name="email" type="email" v-model="selectedPartner.email" placeholder="Email" class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
          </div>
          <div class="modal-actions flex justify-end gap-2 mt-6">
            <button type="button"
              class="cancel-btn bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 font-semibold rounded-lg px-4 py-1.5 hover:from-gray-300 hover:to-gray-400 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out"
              @click="showUpdateModal = false">
              Annuler
            </button>
            <button type="submit"
              class="add-btn bg-gradient-to-r from-blue-800 to-blue-600 text-white font-semibold rounded-lg px-4 py-1.5 hover:from-blue-900 hover:to-blue-700 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out">
              Mettre à jour
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal Suppression -->
    <div v-if="showDeleteModal" class="modal-overlay">
      <div class="modal-content">
        <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Supprimer un Partenaire</h3>
        <p class="text-center mb-4">Êtes-vous sûr de vouloir supprimer <strong>{{ selectedPartner.name }}</strong> ?</p>
        <div class="modal-actions flex justify-end gap-2">
          <button type="button"
            class="cancel-btn bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 font-semibold rounded-lg px-4 py-1.5 hover:from-gray-300 hover:to-gray-400 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out"
            @click="showDeleteModal = false">
            Annuler
          </button>
          <button type="button"
            class="delete-btn bg-gradient-to-r from-red-600 to-red-800 text-white font-semibold rounded-lg px-4 py-1.5 hover:from-red-700 hover:to-red-900 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out"
            @click="deletePartner">
            Oui, Supprimer
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import PartenaireService from '@/services/PartenaireService';
import EditionService from '@/services/EditionService';

export default {
  name: "PartnairAdmin",
  data() {
    return {
      dropdownOpen: false,
      showAddModal: false,
      showUpdateModal: false,
      showDeleteModal: false,
      selectedEdition: null,
      selectedEditionName: '',
      selectedEditionId: null,

      editions: [], // Will be loaded from API
      newPartner: {
        name: '',
        image: null, // Changed from image_url to image for FormData
        edition_id: '',
        description: '',
        phone: '',
        email: ''
      },
      selectedPartner: {
        id: null,
        name: '',
        image: null, // Changed from image_url to image for FormData
        edition_id: '',
        description: '',
        phone: '',
        email: ''
      },
      partners: [] // Will be loaded from API
    };
  },
  async created() {
    // Get edition ID from route params or localStorage
    this.selectedEditionId = localStorage.getItem('selectedEditionId');
    await this.fetchEditions();

    // Set default edition if available
    if (this.selectedEditionId) {
      this.selectedEdition = parseInt(this.selectedEditionId);
      this.newPartner.edition_id = parseInt(this.selectedEditionId);
    }

    await this.fetchPartnersByEdition();
  },
  computed: {
    filteredPartners() {
      return this.partners; // Now we always show the filtered list from API
    }
  },
  watch: {
    '$route.query': {
      immediate: true,
      handler(query) {
        if (query.highlightType === 'partner' && query.highlight) {
          this.highlightPartner(parseInt(query.highlight, 10));
        }
      }
    }
  },
  methods: {
    async fetchEditions() {
      try {
        const response = await EditionService.getAllEditions();
        this.editions = response.data;

        // Si aucune édition n'est sélectionnée, sélectionner la première par défaut
        if (this.selectedEditionId) {
          const selectedEdition = this.editions.find(e => e.id == this.selectedEditionId);
          this.selectedEditionName = selectedEdition ? selectedEdition.name : '';
        }
      } catch (error) {
        console.error('Erreur lors de la récupération des éditions :', error);
      }
    },

    async fetchPartnersByEdition() {
      if (!this.selectedEdition) return;

      try {
        const response = await PartenaireService.getPartenairesByEdition(this.selectedEdition);
        this.partners = response.data;
      } catch (error) {
        console.error('Erreur lors de la récupération des partenaires :', error);
      }
    },

    toggleDropdown() {
      this.dropdownOpen = !this.dropdownOpen;
    },

    onEditOption(editionName) {
      const edition = this.editions.find(e => e.name === editionName);
      if (edition) {
        this.selectedEdition = edition.id;
        // localStorage.setItem('selectedEditionId', edition.id);
        this.newPartner.edition_id = edition.id;
        this.fetchPartnersByEdition();
      }
      this.dropdownOpen = false;
    },

    handleImageUpload(event, type) {
      const file = event.target.files[0];
      if (!file) return;

      if (type === 'new') {
        this.newPartner.image = file;
        // Create preview
        const reader = new FileReader();
        reader.onload = () => {
          this.newPartner.image_url = reader.result;
        };
        reader.readAsDataURL(file);
      }

      if (type === 'update') {
        this.selectedPartner.image = file;
        // Create preview
        const reader = new FileReader();
        reader.onload = () => {
          this.selectedPartner.image_url = reader.result;
        };
        reader.readAsDataURL(file);
      }
    },
    getImageUrl(imagePath) {
      return `http://localhost:8000/storage/${imagePath}`;
    },

    async addPartner() {
      try {
        const formData = new FormData();
        formData.append('name', this.newPartner.name);
        if (this.newPartner.image) {
          formData.append('image', this.newPartner.image);
        }
        formData.append('edition_id', this.newPartner.edition_id);
        formData.append('description', this.newPartner.description);
        formData.append('phone', this.newPartner.phone);
        formData.append('email', this.newPartner.email);

        const response = await PartenaireService.createPartenaire(formData);
        this.partners.push(response.data);
        this.resetNewPartner();
        this.showAddModal = false;
      } catch (error) {
        console.error('Erreur lors de l\'ajout du partenaire :', error);
      }
    },

    resetNewPartner() {
      this.newPartner = {
        name: '',
        image: null,
        image_url: '',
        edition_id: '',
        description: '',
        phone: '',
        email: ''
      };
    },

    openUpdateModal(partner) {
      this.selectedPartner = {
        ...partner,
        image: null,
        image_url: partner.logo || partner.image_url
      };
      this.showUpdateModal = true;
    },

    async updatePartner() {
      try {
        const formData = new FormData();
        formData.append('name', this.selectedPartner.name);
        if (this.selectedPartner.image) {
          formData.append('image', this.selectedPartner.image);
        }
        formData.append('edition_id', this.selectedPartner.edition_id);
        formData.append('description', this.selectedPartner.description);
        formData.append('phone', this.selectedPartner.phone);
        formData.append('email', this.selectedPartner.email);

        const response = await PartenaireService.updatePartenaire(this.selectedPartner.id, formData);

        // Update local data
        const index = this.partners.findIndex(p => p.id === this.selectedPartner.id);
        if (index !== -1) {
          this.partners.splice(index, 1, response.data);
        }

        this.showUpdateModal = false;
      } catch (error) {
        console.error('Erreur lors de la mise à jour du partenaire :', error);
      }
    },

    openDeleteModal(partner) {
      this.selectedPartner = { ...partner };
      this.showDeleteModal = true;
    },

    async deletePartner() {
      try {
        await PartenaireService.deletePartenaire(this.selectedPartner.id);
        this.partners = this.partners.filter(p => p.id !== this.selectedPartner.id);
        this.showDeleteModal = false;
      } catch (error) {
        console.error('Erreur lors de la suppression du partenaire :', error);
      }
    },
    highlightPartner(partnerId) {
      // If partners are already loaded
      if (this.partners.length > 0) {
        this.scrollToPartner(partnerId);
        return;
      }

      // If partners are loading, wait for them
      const checkInterval = setInterval(() => {
        if (this.partners.length > 0) {
          clearInterval(checkInterval);
          this.scrollToPartner(partnerId);
        }
      }, 100);
    },

    scrollToPartner(partnerId) {
      this.$nextTick(() => {
        const element = document.getElementById(`partner-${partnerId}`);
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
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css");

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  padding: 30px;
  border-radius: 12px;
  max-width: 500px;
  width: 90%;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
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
.modal-content select,
.modal-content textarea {
  width: 95%;
  padding: 10px 14px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  margin-bottom: 12px;
  font-size: 14px;
}

.modal-content input:focus,
.modal-content select:focus,
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
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 20px;
}

.card {
  background: rgb(254, 252, 252);
  border-radius: 16px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  padding: 16px;
  border: 2px solid #265985;
  text-align: center;
  position: relative;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
}

.logo {
  height: 60px;
  object-fit: contain;
  margin-bottom: 16px;
  transition: transform 0.3s ease;
}

.card:hover .logo {
  transform: scale(1.1);
}

.name {
  font-weight: 600;
  color: #1f2937;
}

.desc {
  font-size: 14px;
  color: #9ca3af;
  margin-bottom: 16px;
}

.info {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  font-size: 14px;
  color: #4b5563;
  margin-bottom: 8px;
}

.icon {
  color: #8b5cf6;
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