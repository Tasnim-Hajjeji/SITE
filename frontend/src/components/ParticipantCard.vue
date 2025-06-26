<template>
  <div class="container">
    <h1>Participants {{ this.selectedEditionName }} ({{ participants.length }} participants)</h1>

    <div class="actions">
      <div class="dropdown" @click="toggleDropdown">
        <button class="btn edit">Edition ▼</button>
        <ul v-if="dropdownOpen" class="dropdown-menu">
          <li v-for="edition in editions" :key="edition.id" @click="setSelectedEdition(edition.id)">
            {{ edition.name }}
          </li>
        </ul>
      </div>
    </div>

    <div class="cards-wrapper">
      <div class="card" v-for="(participant, index) in participants" :key="index" :id="`participant-${participant.id}`">
        <div class="card-header">
          <div class="title-date">
            <h3 class="title">{{ participant.name }}</h3>
            <span class="date">{{ participant.date }}</span>
          </div>
          <div class="info">
            <p><strong>Country</strong>: {{ participant.country }}</p>
            <p><strong>Profession</strong>: {{ participant.profession }}</p>
            <p><strong>Institution</strong>: {{ participant.institution }}</p>
            <p><strong>Email</strong>: {{ participant.email }}</p>
            <p><strong>Phone</strong>: {{ participant.phone }}</p>
          </div>
          <button class="toggle-btn" @click="toggleDetails(index)">
            {{ participant.showDetails ? 'Less details' : 'More details' }}
            <i :class="['arrow', participant.showDetails ? 'up' : 'down']"></i>
          </button>
        </div>

        <transition name="fade">
          <div v-if="participant.showDetails" class="details">
            <p>Participation: {{ participant.details.participation }}</p>
            <p>Accommodation: {{ participant.details.accommodation }}</p>
            <p>Children: {{ participant.details.children }}</p>
            <p>Adults: {{ participant.details.adults }}</p>
            <p>Single Supplement: {{ participant.details.singleSupplement }}</p>
            <p>Extra Nights: {{ participant.details.extraNights }}</p>
            <p>Payment: {{ participant.details.payment }}</p>
            <p>Total: {{ participant.details.total }}</p>
            <button class="download-btn" @click="openPaymentModal(index)">
              <i class="fas fa-eye"></i> Payment Proof
            </button>
          </div>
        </transition>
        <div class="tools">
          <button class="icon-btn" @click="openUpdateModal(index)">
            <i class="fas fa-edit"></i>
          </button>
          <button class="icon-btn" @click="openDeleteModal(index)">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Update Modal -->
    <transition name="fade">
      <div v-if="showUpdateModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
        <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-md font-poppins max-h-[90vh] overflow-y-auto">
          <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">Update Participant
          </h3>
          <form @submit.prevent="updateParticipant" class="space-y-4">
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
              <input v-model="editParticipant.name" id="name" type="text" placeholder="Enter name"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required />
            </div>
            <div>
              <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
              <input v-model="editParticipant.date" id="date" type="text" placeholder="Enter date (e.g., 12-10-2025)"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required />
            </div>
            <div>
              <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
              <input v-model="editParticipant.country" id="country" type="text" placeholder="Enter country"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required />
            </div>
            <div>
              <label for="profession" class="block text-sm font-medium text-gray-700">Profession</label>
              <input v-model="editParticipant.profession" id="profession" type="text" placeholder="Enter profession"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required />
            </div>
            <div>
              <label for="institution" class="block text-sm font-medium text-gray-700">Institution</label>
              <input v-model="editParticipant.institution" id="institution" type="text" placeholder="Enter institution"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required />
            </div>
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
              <input v-model="editParticipant.email" id="email" type="email" placeholder="Enter email"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required />
            </div>
            <div>
              <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
              <input v-model="editParticipant.phone" id="phone" type="text" placeholder="Enter phone number"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required />
            </div>
            <div>
              <label for="participation" class="block text-sm font-medium text-gray-700">Participation</label>
              <input v-model="editParticipant.details.participation" id="participation" type="text"
                placeholder="Enter participation (e.g., with paper)"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
            </div>
            <div>
              <label for="accommodation" class="block text-sm font-medium text-gray-700">Accommodation</label>
              <input v-model="editParticipant.details.accommodation" id="accommodation" type="text"
                placeholder="Enter accommodation (e.g., yes/no)"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
            </div>
            <div>
              <label for="children" class="block text-sm font-medium text-gray-700">Children</label>
              <input v-model="editParticipant.details.children" id="children" type="number"
                placeholder="Enter number of children"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
            </div>
            <div>
              <label for="adults" class="block text-sm font-medium text-gray-700">Adults</label>
              <input v-model="editParticipant.details.adults" id="adults" type="number"
                placeholder="Enter number of adults"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
            </div>
            <div>
              <label for="singleSupplement" class="block text-sm font-medium text-gray-700">Single Supplement</label>
              <input v-model="editParticipant.details.singleSupplement" id="singleSupplement" type="text"
                placeholder="Enter single supplement (e.g., yes/no)"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
            </div>
            <div>
              <label for="extraNights" class="block text-sm font-medium text-gray-700">Extra Nights</label>
              <input v-model="editParticipant.details.extraNights" id="extraNights" type="number"
                placeholder="Enter number of extra nights"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
            </div>
            <div>
              <label for="payment" class="block text-sm font-medium text-gray-700">Payment</label>
              <select v-model="editParticipant.details.payment" id="payment"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                <option value="" disabled>Select payment method</option>
                <option value="bon de commande">Bon de commande</option>
                <option value="virement">Virement</option>
                <option value="chèque">Chèque</option>
              </select>
            </div>
            <div>
              <label for="total" class="block text-sm font-medium text-gray-700">Total</label>
              <input v-model="editParticipant.details.total" id="total" type="text" placeholder="Enter total amount"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
            </div>
            <div class="flex justify-end space-x-3 mt-6">
              <button type="button" class="btn cancel" @click="toggleUpdateModal">
                Cancel
              </button>
              <button type="submit" class="btn add">
                Update
              </button>
            </div>
          </form>
        </div>
      </div>
    </transition>

    <!-- Delete Modal -->
    <transition name="fade">
      <div v-if="showDeleteModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
        <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-md font-poppins max-h-[90vh] overflow-y-auto">
          <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">Delete Participant
          </h3>
          <p class="text-gray-600 mb-6 text-center">Are you sure you want to delete <strong>{{ editParticipant.name
          }}</strong>?</p>
          <div class="flex justify-end space-x-3">
            <button type="button" class="btn cancel" @click="toggleDeleteModal">
              Cancel
            </button>
            <button type="button" class="btn delete" @click="deleteParticipant">
              Yes, Delete
            </button>
          </div>
        </div>
      </div>
    </transition>

    <!-- Payment Proof Modal -->
    <transition name="fade">
      <div v-if="showPaymentModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
        <div class="bg-white p-6 rounded-lg w-full max-w-2xl shadow-md font-poppins max-h-[90vh] overflow-y-auto">
          <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">Payment Proof</h3>
          <img :src="getImageUrl(this.currentPaymentProof)" alt="Payment Proof"
            class="w-full h-auto max-h-[70vh] object-contain mb-4" />
          <div class="flex justify-end space-x-3">
            <button class="btn confirm" @click="downloadPaymentProof">
              <i class="fas fa-download"></i> Download Payment Proof
            </button>
            <button class="btn cancel" @click="closePaymentModal">Cancel</button>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import ParticipantService from '@/services/ParticipantService';
import EditionService from '@/services/EditionService';

export default {
  name: "ParticipantCard",
  data() {
    return {
      dropdownOpen: false,
      showUpdateModal: false,
      showDeleteModal: false,
      showPaymentModal: false,
      editIndex: null,
      paymentIndex: null,
      editions: [], // To store fetched editions
      selectedEditionName: '',
      editParticipant: {
        name: '',
        date: '',
        country: '',
        profession: '',
        institution: '',
        email: '',
        phone: '',
        showDetails: false,
        details: {
          participation: '',
          accommodation: '',
          children: 0,
          adults: 0,
          singleSupplement: '',
          extraNights: 0,
          payment: '',
          total: ''
        }
      },
      participants: [], // Will be populated from API
      selectedEditionId: null,
      currentPaymentProof: null
    };
  },
  async created() {
    await this.fetchEditions();
    this.setSelectedEdition();
    await this.fetchParticipants();
  },
  watch: {
    '$route.query': {
      immediate: true,
      handler(query) {
        if (query.highlightType === 'participant' && query.highlight) {
          this.highlightParticipant(parseInt(query.highlight, 10));
        }
      }
    }
  },
  methods: {
    async fetchEditions() {
      try {
        const response = await EditionService.getAllEditions();
        this.editions = response.data;
        this.selectedEditionId = localStorage.getItem('selectedEditionId') || this.editions[0].id;
        const selectedEdition = this.editions.find(e => e.id == this.selectedEditionId);
        this.selectedEditionName = selectedEdition ? selectedEdition.name : '';
      } catch (error) {
        console.error('Error fetching editions:', error);
      }
    },
    setSelectedEdition(editionId) {
      this.selectedEditionId = editionId || localStorage.getItem('selectedEditionId');
      const selectedEdition = this.editions.find(e => e.id == this.selectedEditionId);
      this.selectedEditionName = selectedEdition ? selectedEdition.name : '';
      this.dropdownOpen = false;
      this.fetchParticipants();
    },
    async fetchParticipants() {
      if (!this.selectedEditionId) return;

      try {
        const response = await ParticipantService.getParticipantsByEdition(this.selectedEditionId);
        this.participants = response.data.map(p => ({
          ...p,
          showDetails: false,
          // Map backend fields to frontend structure
          name: `${p.prenom} ${p.nom}`,
          date: p.created_at ? new Date(p.created_at).toLocaleDateString() : 'N/A',
          country: p.pays,
          profession: p.fonction,
          institution: p.etablissement,
          email: p.email,
          phone: p.tel,
          details: {
            participation: p.participation || 'N/A',
            accommodation: p.accommodation ? 'Yes' : 'No',
            children: p.num_enfant,
            adults: p.num_adulte,
            singleSupplement: p.supp_single ? 'Yes' : 'No',
            extraNights: p.supp_nuit ? 1 : 0,
            payment: p.methode_paie,
            total: p.prix_total
          }
        }));
      } catch (error) {
        console.error('Error fetching participants:', error);
      }
    },
    toggleDropdown() {
      this.dropdownOpen = !this.dropdownOpen;
    },
    async onEditOption(editionId) {
      this.dropdownOpen = false;
      this.selectedEditionId = editionId;
      localStorage.setItem('selectedEditionId', editionId);
      await this.fetchParticipants();
    },
    toggleDetails(index) {
      this.participants = this.participants.map((p, i) => {
        if (i === index) {
          return { ...p, showDetails: !p.showDetails };
        }
        return { ...p, showDetails: false };
      });
    },
    openUpdateModal(index) {
      this.editParticipant = { ...this.participants[index], details: { ...this.participants[index].details } };
      this.editIndex = index;
      this.showUpdateModal = true;
    },
    toggleUpdateModal() {
      this.showUpdateModal = !this.showUpdateModal;
      if (!this.showUpdateModal) this.resetEditParticipant();
    },
    async updateParticipant() {
      try {
        const participantData = {
          // Map frontend fields to backend structure
          nom: this.editParticipant.name.split(' ')[1] || '',
          prenom: this.editParticipant.name.split(' ')[0] || '',
          email: this.editParticipant.email,
          fonction: this.editParticipant.profession,
          tel: this.editParticipant.phone,
          pays: this.editParticipant.country,
          etablissement: this.editParticipant.institution,
          num_enfant: this.editParticipant.details.children,
          num_adulte: this.editParticipant.details.adults,
          supp_single: this.editParticipant.details.singleSupplement === 'Yes',
          supp_nuit: this.editParticipant.details.extraNights ,
          prix_total: this.editParticipant.details.total,
          methode_paie: this.editParticipant.details.payment,
          edition_id: this.selectedEditionId
        };
        console.log('Partcipant id:', this.participants[this.editIndex].id);
        console.log('Updating participant with data:', participantData);
        await ParticipantService.updateParticipant(
          this.participants[this.editIndex].id,
          participantData
        );

        this.toggleUpdateModal();
        await this.fetchParticipants(); // Refresh the list
      } catch (error) {
        console.error('Error updating participant:', error);
      }
    },
    openDeleteModal(index) {
      this.editParticipant = { ...this.participants[index], details: { ...this.participants[index].details } };
      this.editIndex = index;
      this.showDeleteModal = true;
    },
    toggleDeleteModal() {
      this.showDeleteModal = !this.showDeleteModal;
      if (!this.showDeleteModal) this.resetEditParticipant();
    },
    async deleteParticipant() {
      try {
        await ParticipantService.deleteParticipant(this.participants[this.editIndex].id);
        this.toggleDeleteModal();
        await this.fetchParticipants(); // Refresh the list
      } catch (error) {
        console.error('Error deleting participant:', error);
      }
    },
    async openPaymentModal(index) {
      this.paymentIndex = index;
      try {
        const response = await ParticipantService.getParticipant(this.participants[index].id);
        this.currentPaymentProof = response.data.recu_paie;
        this.showPaymentModal = true;
      } catch (error) {
        console.error('Error fetching payment proof:', error);
      }
    },
    getImageUrl(imagePath) {
      return `http://localhost:8000/storage/${imagePath}`;
    },
    closePaymentModal() {
      this.showPaymentModal = false;
      this.paymentIndex = null;
    },
    async downloadPaymentProof() {
      if (!this.currentPaymentProof) return;

      try {
        const imageUrl = this.getImageUrl(this.currentPaymentProof);

        // Fetch the image as blob
        const response = await fetch(imageUrl);
        const blob = await response.blob();

        // Create object URL from blob
        const url = window.URL.createObjectURL(blob);

        // Create download link
        const link = document.createElement('a');
        link.href = url;
        link.download = `payment_proof_${this.participants[this.paymentIndex].name.replace(/\s+/g, '_')}.${this.currentPaymentProof.split('.').pop()}`;

        // Append to body, click, and remove
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        // Clean up object URL
        window.URL.revokeObjectURL(url);

        this.closePaymentModal();
      } catch (error) {
        console.error('Error downloading payment proof:', error);
        // Fallback: open in new tab
        window.open(this.getImageUrl(this.currentPaymentProof), '_blank');
      }
    },
    resetEditParticipant() {
      this.editParticipant = {
        name: '',
        date: '',
        country: '',
        profession: '',
        institution: '',
        email: '',
        phone: '',
        showDetails: false,
        details: {
          participation: '',
          accommodation: '',
          children: 0,
          adults: 0,
          singleSupplement: '',
          extraNights: 0,
          payment: '',
          total: ''
        }
      };
      this.editIndex = null;
    },
    highlightParticipant(participantId) {
      // If participants are already loaded
      if (this.participants.length > 0) {
        this.scrollToParticipant(participantId);
        return;
      }

      // If participants are loading, wait for them
      const checkInterval = setInterval(() => {
        if (this.participants.length > 0) {
          clearInterval(checkInterval);
          this.scrollToParticipant(participantId);
        }
      }, 100);
    },

    scrollToParticipant(participantId) {
      this.$nextTick(() => {
        const element = document.getElementById(`participant-${participantId}`);
        if (element) {
          element.scrollIntoView({ behavior: 'smooth', block: 'center' });
          element.classList.add('highlighted');
          setTimeout(() => {
            element.classList.remove('highlighted');
          }, 3000);
        }
      });
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

h1 {
  font-size: 24px;
  font-weight: bold;
  color: #2c3e50;
  margin-bottom: 20px;
}

.actions {
  display: flex;
  justify-content: flex-end;
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

.confirm {
  background: #4caf50;
  color: white;
  border: none;
  padding: 0.3rem 1rem;
  border-radius: 5px;
  display: flex;
  align-items: center;
  gap: 5px;
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

.cards-wrapper {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: flex-start;
}

.card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
  padding: 20px;
  width: 300px;
  margin-bottom: 20px;
  transition: box-shadow 0.3s ease, transform 0.3s ease;
  display: flex;
  flex-direction: column;
  border: #1e476b 2px solid;
  justify-content: space-between;
  cursor: pointer;
  position: relative;
}

.card:hover {
  box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
  transform: translateY(-8px) scale(1.03);
  border-color: #3a3a83;
}

.card-header .title-date {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 32px;
}

.title {
  font-weight: 600;
  color: #3a3a83;
}

.date {
  font-size: 14px;
  color: gray;
}

.info p {
  font-size: 14px;
  margin: 4px 0;
}

.toggle-btn {
  background: none;
  border: none;
  color: #3a3a83;
  font-weight: 500;
  cursor: pointer;
  margin-top: 10px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
}

.arrow {
  margin-left: 8px;
  border: solid #3a3a83;
  border-width: 0 2px 2px 0;
  display: inline-block;
  padding: 5px;
  transition: 0.3s ease;
}

.down {
  transform: rotate(45deg);
}

.up {
  transform: rotate(-135deg);
}

.details {
  font-size: 14px;
  margin-top: 10px;
  color: #333;
}

.download-btn {
  background: #eef6ff;
  color: #3a3a83;
  border: 1px solid #3a3a83;
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 13px;
  margin-top: 10px;
  cursor: pointer;
}

.download-btn i {
  margin-right: 5px;
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

/* Animation */
.fade-enter-active,
.fade-leave-active {
  transition: all 0.4s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(-5px);
}

/* Modal styles */
.font-poppins {
  font-family: 'Poppins', sans-serif;
}

/* Responsive */
@media (max-width: 960px) {
  .cards-wrapper {
    justify-content: center;
  }
}

@media (max-width: 480px) {
  .cards-wrapper {
    flex-direction: column;
    align-items: center;
  }

  .card {
    width: 90%;
  }

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