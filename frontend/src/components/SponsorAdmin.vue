```vue
<template>
  <div class="container">
    <h1 class="title">Les Sponsors de {{ this.selectedEditionName }}</h1>

    <div class="actions">
      <button class="btn add" @click="toggleAddModal">
        <span class="plus">+</span> Ajouter un Sponsor
      </button>
      <div class="dropdown-container">
        <div class="dropdown" @click="toggleFilter">
          <button class="btn edit">Filtrer par ▼</button>
          <ul v-if="showFilter" class="dropdown-menu">
            <li @click="setFilter('all')">Tous</li>
            <li @click="setFilter('confirmed')">Confirmés</li>
            <li @click="setFilter('pending')">En attente</li>
          </ul>
        </div>
        <div class="dropdown" @click="toggleDropdown">
          <button class="btn edit">Édition ▼</button>
          <ul v-if="dropdownOpen" class="dropdown-menu">
            <li v-for="edition in editions" :key="edition.id" @click="onEditOption(edition.name)">
              {{ edition.name }}
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div v-if="isLoading" class="loading-message">
      Chargement des sponsors...
    </div>

    <div v-else-if="sponsors.length === 0" class="empty-message">
      Aucun sponsor disponible.
    </div>

    <div v-else class="grid">
      <div v-for="(sponsor) in filteredSponsors" :key="sponsor.id" class="card" :id="`sponsor-${sponsor.id}`">
        <img :src="getImageUrl(sponsor.logo)" alt="logo" class="logo" />
        <h2 class="name">{{ sponsor.name }}</h2>
        <p class="desc">{{ sponsor.description }}</p>
        <div class="info"><i class="fas fa-phone icon"></i>{{ sponsor.phone }}</div>
        <div class="info"><i class="fas fa-envelope icon"></i>{{ sponsor.email }}</div>

        <div class="tools">
          <button class="icon-btn" @click="openDeleteModal(sponsor.id)">
            <i class="fas fa-trash"></i>
          </button>
          <button class="icon-btn" @click="openUpdateModal(sponsor)">
            <i class="fas fa-edit"></i>
          </button>
        </div>

        <div class="buttons" v-if="sponsor.etat === 'pending'">
          <button class="reject" @click="deleteSponsor(sponsor.id)">Rejeter</button>
          <button class="confirm" @click="updateStatus(sponsor.id, 'confirmed')">Confirmer</button>
        </div>

        <div v-if="sponsor.etat !== 'pending'" class="status-badge" :class="{ confirmed: sponsor.etat === 'confirmed', rejected: sponsor.etat === 'rejected' }">
          <span v-if="sponsor.etat === 'confirmed'" class="badge-content"><i class="fas fa-check-circle"></i> Sponsor</span>
          <span v-else-if="sponsor.etat === 'rejected'" class="badge-content"><i class="fas fa-times-circle"></i> Rejeté</span>
        </div>
      </div>
    </div>

    <!-- Modal Ajouter -->
    <div v-if="showAddModal" class="modal-overlay">
      <div class="modal-content">
        <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Ajouter un Partenaire</h3>
        <form @submit.prevent="addSponsor" class="space-y-0">
          <div>
            <label for="name_input" class="block mb-1 text-xs text-gray-500 font-medium">Nom de l'entreprise</label>
            <input id="name_input" v-model="newSponsor.name" placeholder="Nom de l'entreprise" class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
          </div>
          <div>
            <label for="adresse_input" class="block mb-1 text-xs text-gray-500 font-medium">Adresse</label>
            <input id="adresse_input" v-model="newSponsor.adresse" placeholder="Adresse" class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
          </div>
          <div>
            <label for="phone_input" class="block mb-1 text-xs text-gray-500 font-medium">Téléphone</label>
            <input id="phone_input" v-model="newSponsor.phone" placeholder="Téléphone" class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
          </div>
          <div>
            <label for="email_input" class="block mb-1 text-xs text-gray-500 font-medium">Email</label>
            <input id="email_input" v-model="newSponsor.email" placeholder="Email" class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
          </div>
          <div>
            <label for="desc_input" class="block mb-1 text-xs text-gray-500 font-medium">Description</label>
            <textarea id="desc_input" v-model="newSponsor.description" placeholder="Description" class="w-[95%] p-2 border border-gray-300 rounded-lg" required></textarea>
          </div>
          <div>
            <label for="logo_input" class="block mb-1 text-xs text-gray-500 font-medium">Logo</label>
            <input id="logo_input" type="file" @change="onFileChange" class="w-[95%] p-2 border border-gray-300 rounded-lg" />
          </div>
          <div class="modal-actions flex justify-end gap-2 mt-6">
            <button type="button"
              class="cancel-btn bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 font-semibold rounded-lg px-4 py-1.5 hover:from-gray-300 hover:to-gray-400 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out"
              @click="toggleAddModal">
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

    <!-- Modal Mettre à jour -->
    <div v-if="showUpdateModal" class="modal-overlay">
      <div class="modal-content">
        <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Mettre à jour le Partenaire</h3>
        <form @submit.prevent="updateSponsor" class="space-y-0">
          <div>
            <label for="update_name_input" class="block mb-1 text-xs text-gray-500 font-medium">Nom de l'entreprise</label>
            <input id="update_name_input" v-model="selectedSponsor.name" placeholder="Nom de l'entreprise" class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
          </div>
          <div>
            <label for="update_adresse_input" class="block mb-1 text-xs text-gray-500 font-medium">Adresse</label>
            <input id="update_adresse_input" v-model="selectedSponsor.adresse" placeholder="Adresse" class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
          </div>
          <div>
            <label for="update_phone_input" class="block mb-1 text-xs text-gray-500 font-medium">Téléphone</label>
            <input id="update_phone_input" v-model="selectedSponsor.phone" placeholder="Téléphone" class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
          </div>
          <div>
            <label for="update_email_input" class="block mb-1 text-xs text-gray-500 font-medium">Email</label>
            <input id="update_email_input" v-model="selectedSponsor.email" placeholder="Email" class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
          </div>
          <div>
            <label for="update_desc_input" class="block mb-1 text-xs text-gray-500 font-medium">Description</label>
            <textarea id="update_desc_input" v-model="selectedSponsor.description" placeholder="Description" class="w-[95%] p-2 border border-gray-300 rounded-lg" required></textarea>
          </div>
          <div>
            <label for="update_logo_input" class="block mb-1 text-xs text-gray-500 font-medium">Logo</label>
            <input id="update_logo_input" type="file" @change="onFileChangeUpdate" class="w-[95%] p-2 border border-gray-300 rounded-lg" />
          </div>
          <div class="modal-actions flex justify-end gap-2 mt-6">
            <button type="button"
              class="cancel-btn bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 font-semibold rounded-lg px-4 py-1.5 hover:from-gray-300 hover:to-gray-400 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out"
              @click="closeUpdateModal">
              Annuler
            </button>
            <button type="submit"
              class="add-btn bg-gradient-to-r from-blue-800 to-blue-600 text-white font-semibold rounded-lg px-4 py-1.5 hover:from-blue-900 hover:to-blue-700 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out">
              Sauvegarder
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal Supprimer -->
    <div v-if="showDeleteModal" class="modal-overlay">
      <div class="modal-content">
        <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Supprimer le Sponsor</h3>
        <p class="text-center mb-4">Êtes-vous sûr de vouloir supprimer ce sponsor ?</p>
        <div class="modal-actions flex justify-end gap-2">
          <button type="button"
            class="cancel-btn bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 font-semibold rounded-lg px-4 py-1.5 hover:from-gray-300 hover:to-gray-400 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out"
            @click="cancelDelete">
            Annuler
          </button>
          <button type="button"
            class="delete-btn bg-gradient-to-r from-red-600 to-red-800 text-white font-semibold rounded-lg px-4 py-1.5 hover:from-red-700 hover:to-red-900 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out"
            @click="confirmDelete">
            Confirmer la Suppression
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SponsorService from '@/services/SponsorService'
import EditionService from '@/services/EditionService'
import { useRoute } from 'vue-router'

export default {
  data() {
    return {
      editionId: null,
      selectedEditionName: '',
      editions: [],
      sponsors: [],
      filter: 'all',
      showFilter: false,
      showAddModal: false,
      showUpdateModal: false,
      showDeleteModal: false,
      dropdownOpen: false,
      isLoading: true,
      newSponsor: {
        name: '',
        adresse: '',
        phone: '',
        email: '',
        description: '',
        logo: null,
        edition_id: localStorage.getItem('selectedEditionId') || useRoute().params.editionId,
        etat: 'confirmed',
      },
      selectedSponsor: {
        id: null,
        name: '',
        adresse: '',
        phone: '',
        email: '',
        description: '',
        logo: null,
        edition_id: null,
        etat: '',
      },
      selectedFile: null,
      selectedUpdateFile: null,
      deleteSponsorId: null,
    }
  },
  computed: {
    filteredSponsors() {
      if (this.filter === 'all') return this.sponsors
      if (this.filter === 'confirmed') return this.sponsors.filter(s => s.etat === 'confirmed')
      if (this.filter === 'pending') return this.sponsors.filter(s => s.etat === 'pending')
      return []
    }
  },
  async created() {
    this.editionId = localStorage.getItem('selectedEditionId') || useRoute().params.editionId
    await this.fetchEditions()
    await this.fetchSponsors()
    console.log('Nom de l\'édition sélectionnée', this.selectedEditionName)
  },
  watch: {
    '$route.query': {
      immediate: true,
      handler(query) {
        if (query.highlightType === 'sponsor' && query.highlight) {
          this.highlightSponsor(parseInt(query.highlight, 10))
        }
      }
    }
  },
  methods: {
    async fetchEditions() {
      try {
        const response = await EditionService.getAllEditions()
        this.editions = response.data

        // Si aucune édition n'est sélectionnée, sélectionner la première par défaut
        if (this.editionId) {
          const selectedEdition = this.editions.find(e => e.id == this.editionId)
          this.selectedEditionName = selectedEdition ? selectedEdition.name : ''
        }
      } catch (error) {
        console.error('Erreur lors de la récupération des éditions :', error)
      }
    },
    async fetchSponsors() {
      try {
        this.isLoading = true
        const response = await SponsorService.getSponsorsByEdition(this.editionId)
        this.sponsors = response.data
        console.log('Partenaires récupérés :', this.sponsors)
      } catch (error) {
        console.error('Erreur lors de la récupération des partenaires :', error)
      } finally {
        this.isLoading = false
      }
    },
    toggleFilter() {
      this.showFilter = !this.showFilter
    },
    setFilter(type) {
      this.filter = type
      this.showFilter = false
    },
    toggleDropdown() {
      this.dropdownOpen = !this.dropdownOpen
    },
    onEditOption(editionName) {
      const edition = this.editions.find(e => e.name === editionName)
      if (edition) {
        this.editionId = edition.id
        // localStorage.setItem('selectedEditionId', edition.id)
        this.fetchSponsors() // Rafraîchir les partenaires pour l'édition sélectionnée
      }
      this.dropdownOpen = false
    },
    async updateStatus(id, status) {
      try {
        await SponsorService.updateSponsor(id, { etat: status })
        await this.fetchSponsors() // Rafraîchir la liste
      } catch (error) {
        console.error('Erreur lors de la mise à jour du statut du partenaire :', error)
      }
    },
    openDeleteModal(id) {
      this.deleteSponsorId = id
      this.showDeleteModal = true
    },
    async confirmDelete() {
      if (this.deleteSponsorId) {
        await this.deleteSponsor(this.deleteSponsorId)
      }
      this.cancelDelete()
    },
    cancelDelete() {
      this.showDeleteModal = false
      this.deleteSponsorId = null
    },
    async deleteSponsor(id) {
      if (confirm('Êtes-vous sûr de vouloir supprimer ce partenaire ?')) {
        try {
          await SponsorService.deleteSponsor(id)
          await this.fetchSponsors() // Rafraîchir la liste
        } catch (error) {
          console.error('Erreur lors de la suppression du partenaire :', error)
        }
      }
    },
    onFileChange(event) {
      const file = event.target.files[0]
      if (file) {
        this.selectedFile = file
        this.newSponsor.logo = URL.createObjectURL(file)
      }
    },
    onFileChangeUpdate(event) {
      const file = event.target.files[0]
      if (file) {
        this.selectedUpdateFile = file
        this.selectedSponsor.logo = URL.createObjectURL(file)
      }
    },
    async addSponsor() {
      try {
        const formData = new FormData()
        formData.append('name', this.newSponsor.name)
        formData.append('adresse', this.newSponsor.adresse)
        formData.append('phone', this.newSponsor.phone)
        formData.append('email', this.newSponsor.email)
        formData.append('description', this.newSponsor.description)
        formData.append('edition_id', this.newSponsor.edition_id)
        formData.append('etat', this.newSponsor.etat)
        if (this.selectedFile) {
          formData.append('logo', this.selectedFile)
        }

        await SponsorService.createSponsor(formData)
        await this.fetchSponsors() // Rafraîchir la liste

        // Réinitialiser le formulaire
        this.resetNewSponsor()
        this.toggleAddModal()
      } catch (error) {
        console.error('Erreur lors de l\'ajout du partenaire :', error)
      }
    },
    async updateSponsor() {
      try {
        const formData = new FormData()
        formData.append('name', this.selectedSponsor.name)
        formData.append('adresse', this.selectedSponsor.adresse)
        formData.append('phone', this.selectedSponsor.phone)
        formData.append('email', this.selectedSponsor.email)
        formData.append('description', this.selectedSponsor.description)
        formData.append('edition_id', this.selectedSponsor.edition_id)
        formData.append('etat', this.selectedSponsor.etat)
        if (this.selectedUpdateFile) {
          formData.append('logo', this.selectedUpdateFile)
        }

        await SponsorService.updateSponsor(this.selectedSponsor.id, formData)
        await this.fetchSponsors() // Rafraîchir la liste
        this.closeUpdateModal()
      } catch (error) {
        console.error('Erreur lors de la mise à jour du partenaire :', error)
      }
    },
    toggleAddModal() {
      this.showAddModal = !this.showAddModal
      if (!this.showAddModal) {
        this.resetNewSponsor()
      }
    },
    resetNewSponsor() {
      this.newSponsor = {
        name: '',
        adresse: '',
        phone: '',
        email: '',
        description: '',
        logo: null,
        edition_id: localStorage.getItem('selectedEditionId') || useRoute().params.editionId,
        etat: 'confirmed',
      }
      this.selectedFile = null
    },
    openUpdateModal(sponsor) {
      this.selectedSponsor = { ...sponsor }
      this.showUpdateModal = true
    },
    closeUpdateModal() {
      this.showUpdateModal = false
      this.selectedSponsor = {
        id: null,
        name: '',
        adresse: '',
        phone: '',
        email: '',
        description: '',
        logo: null,
        edition_id: null,
        etat: '',
      }
      this.selectedUpdateFile = null
    },
    getImageUrl(imagePath) {
      return `http://localhost:8000/storage/${imagePath}`
    },
    highlightSponsor(sponsorId) {
      if (this.sponsors.length > 0) {
        this.scrollToSponsor(sponsorId)
        return
      }

      const checkInterval = setInterval(() => {
        if (this.sponsors.length > 0) {
          clearInterval(checkInterval)
          this.scrollToSponsor(sponsorId)
        }
      }, 100)
    },
    scrollToSponsor(sponsorId) {
      this.$nextTick(() => {
        const element = document.getElementById(`sponsor-${sponsorId}`)
        if (element) {
          element.scrollIntoView({ behavior: 'smooth', block: 'center' })
          element.classList.add('highlighted')
          setTimeout(() => {
            element.classList.remove('highlighted')
          }, 3000)
        }
      })
    },
  }
}
</script>

<style scoped>
.highlighted {
  animation: highlight 3s ease;
  box-shadow: 0 0 0 3px rgba(56, 161, 105, 0.5);
  transform: translateY(-2px);
}

@keyframes highlight {
  0% {
    box-shadow: 0 0 0 4px rgba(56, 161, 105, 0.8);
  }
  70% {
    box-shadow: 0 0 0 4px rgba(56, 161, 105, 0.8);
  }
  100% {
    box-shadow: none;
    transform: translateY(0);
  }
}

.loading-message,
.empty-message {
  text-align: center;
  padding: 2rem;
  color: #666;
  font-style: italic;
}

/* Nouveau style pour le badge de statut */
.status-badge {
  position: absolute;
  bottom: 10px;
  right: 10px;
  display: flex;
  align-items: center;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 500;
  transition: all 0.3s ease;
}

.status-badge.confirmed {
  background-color: #f7f7f7;
  color: rgb(60, 167, 83);
  border: rgb(60, 167, 83) 1px solid;
}

.status-badge.pending {
  background-color: #ffffff;
  color: #ffcc00;
  border: #ffcc00 1px solid;
}

.status-badge.rejected {
  background-color: #ffffff;
  color: #F44336;
  border: #F44336 1px solid;
}

.badge-content {
  display: flex;
  align-items: center;
  gap: 4px;
}

.badge-content i {
  font-size: 14px;
}

/* Keep all other existing styles exactly the same */
.container {
  padding: 2rem;
  font-family: Arial, sans-serif;
}

.title {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 1rem;
  color: #333;
}

.actions {
  margin-top: 3rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
}

.dropdown-container {
  display: flex;
  gap: 1rem;
}

.btn {
  padding: 0.5rem 1rem;
  border-radius: 9999px;
  font-weight: 500;
  font-size: 14px;
  cursor: pointer;
  transition: 0.3s ease;
}

.btn.add {
  background-color: #265985;
  color: white;
  border: none;
}

.btn.add:hover {
  background-color: #1e476b;
  transform: scale(1.05);
}

.btn.edit {
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
  gap: 1rem;
  margin-top: 2rem;
}

.card {
  border: 1px solid #ddd;
  border-radius: 10px;
  padding: 1rem;
  position: relative;
  background: #fff;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.logo {
  width: 100%;
  max-height: 80px;
  object-fit: contain;
}

.name {
  font-weight: bold;
  margin-top: 0.5rem;
}

.desc {
  font-size: 14px;
  color: #555;
  margin: 0.5rem 0;
}

.info {
  font-size: 13px;
  color: #444;
  margin-bottom: 4px;
  display: flex;
  align-items: center;
  gap: 6px;
}

.tools {
  position: absolute;
  top: 10px;
  right: 10px;
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

.buttons {
  display: flex;
  justify-content: space-between;
  margin-top: 1rem;
}

.reject {
  background: #eb5a5a;
  color: white;
  border: none;
  padding: 0.3rem 1rem;
  border-radius: 5px;
}

.confirm {
  background: #4a90e2;
  color: white;
  border: none;
  padding: 0.3rem 1rem;
  border-radius: 5px;
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

@media (max-width: 600px) {
  .grid {
    grid-template-columns: 1fr;
  }
}
</style>
```