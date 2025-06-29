<template>
  <div class="container">
    <h1>Participants {{ selectedEditionName }} ({{ participants.length }} participants)</h1>

    <div class="actions" style="display: flex; justify-content: space-between; align-items: center;">
      <button @click="downloadParticipantListPDF" class="btn download" style="background-color: #3490dc; color: white; padding: 8px 12px; border-radius: 5px; border:none; cursor:pointer;">
        <i class="fas fa-file-pdf"></i> Télécharger liste des participants
      </button>

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
            <p><strong>Pays</strong>: {{ participant.country }}</p>
            <p><strong>Fonction</strong>: {{ participant.profession }}</p>
            <p><strong>Etablissement</strong>: {{ participant.institution }}</p>
            <p><strong>Email</strong>: {{ participant.email }}</p>
            <p><strong>Téléphone</strong>: {{ participant.phone }}</p>
          </div>
          <button class="toggle-btn" @click="toggleDetails(index)">
            {{ participant.showDetails ? 'Moins de details' : 'Plus de details' }}
            <i :class="['arrow', participant.showDetails ? 'up' : 'down']"></i>
          </button>
        </div>

        <transition name="fade">
          <div v-if="participant.showDetails" class="details">
            <p>Participation: {{ participant.details.participation }}</p>
            <p>Hébergement: {{ participant.details.accommodation }}</p>
            <p>Enfants: {{ participant.details.children }}</p>
            <p>Adultes: {{ participant.details.adults }}</p>
            <p>Supplement Single: {{ participant.details.singleSupplement }}</p>
            <p>Nuits Supplimentaires: {{ participant.details.extraNights }}</p>
            <p>Façon de paie: {{ participant.details.payment }}</p>
            <p>Total: {{ participant.details.total }}&nbsp;{{ participant.country === 'tn' ? "DT" : "€" }}</p>
            <button v-if="participant.details.payment !== 'Cash'" class="download-btn" @click="openPaymentModal(index)">
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
      <div v-if="showUpdateModal" class="modal-overlay">
        <div class="modal-content">
          <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Update Participant</h3>
          <form @submit.prevent="updateParticipant" class="space-y-4">
            <!-- input fields (same as your original form) -->
            <!-- ... جميع حقول النموذج ... -->
            <div class="modal-actions flex justify-end gap-2 mt-6">
              <button type="button" class="cancel-btn" @click="toggleUpdateModal">
                Cancel
              </button>
              <button type="submit" class="add-btn">
                Update
              </button>
            </div>
          </form>
        </div>
      </div>
    </transition>

    <!-- Delete Modal -->
    <transition name="fade">
      <div v-if="showDeleteModal" class="modal-overlay">
        <div class="modal-content">
          <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Delete Participant</h3>
          <p class="text-gray-600 mb-4 text-center">Are you sure you want to delete <strong>{{ editParticipant.name }}</strong>?</p>
          <div class="modal-actions flex justify-end gap-2 mt-6">
            <button type="button" class="cancel-btn" @click="toggleDeleteModal">
              Cancel
            </button>
            <button type="button" class="delete-btn" @click="deleteParticipant">
              Yes, Delete
            </button>
          </div>
        </div>
      </div>
    </transition>

    <!-- Payment Proof Modal -->
    <transition name="fade">
      <div v-if="showPaymentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
        <div class="bg-white p-6 rounded-lg w-full max-w-2xl shadow-md font-poppins max-h-[90vh] overflow-y-auto">
          <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">Payment Proof</h3>
          <img :src="getImageUrl(currentPaymentProof)" alt="Payment Proof" class="w-full h-auto max-h-[70vh] object-contain mb-4" />
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
import { jsPDF } from "jspdf";
import autoTable from 'jspdf-autotable';

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
      editions: [],
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
      participants: [],
      selectedEditionId: null,
      currentPaymentProof: null
    };
  },
  async created() {
    await this.fetchEditions();
    this.setSelectedEdition();
    await this.fetchParticipants();
  },
  methods: {
    async fetchEditions() {
      try {
        const response = await EditionService.getAllEditions();
        this.editions = response.data;
        this.selectedEditionId = localStorage.getItem('selectedEditionId') || (this.editions[0] && this.editions[0].id);
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
          },
          paymentProof: p.paymentProof || null
        }));
      } catch (error) {
        console.error('Error fetching participants:', error);
      }
    },
    toggleDropdown() {
      this.dropdownOpen = !this.dropdownOpen;
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
          supp_nuit: this.editParticipant.details.extraNights,
          prix_total: this.editParticipant.details.total,
          methode_paie: this.editParticipant.details.payment,
          edition_id: this.selectedEditionId
        };
        await ParticipantService.updateParticipant(
          this.participants[this.editIndex].id,
          participantData
        );

        this.toggleUpdateModal();
        await this.fetchParticipants();
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
        await this.fetchParticipants();
      } catch (error) {
        console.error('Error deleting participant:', error);
      }
    },
    async openPaymentModal(index) {
      this.paymentIndex = index;
      this.currentPaymentProof = this.participants[index].paymentProof || null;
      this.showPaymentModal = true;
    },
    closePaymentModal() {
      this.showPaymentModal = false;
      this.currentPaymentProof = null;
    },
    getImageUrl(imagePath) {
      return imagePath ? `${process.env.VUE_APP_BASE_URL}/uploads/${imagePath}` : '';
    },
    downloadPaymentProof() {
      if (!this.currentPaymentProof) return;
      const link = document.createElement('a');
      link.href = this.getImageUrl(this.currentPaymentProof);
      link.download = 'payment-proof.jpg';
      link.click();
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

    downloadParticipantListPDF() {
      if (!this.participants.length) {
        alert("No participants to generate PDF.");
        return;
      }

      // ترتيب المشاركين حسب اللقب (Last Name) أبجدياً
      const sortedParticipants = [...this.participants].sort((a, b) => {
        const lastNameA = a.name.split(' ')[1] ? a.name.split(' ')[1].toLowerCase() : '';
        const lastNameB = b.name.split(' ')[1] ? b.name.split(' ')[1].toLowerCase() : '';
        if (lastNameA < lastNameB) return -1;
        if (lastNameA > lastNameB) return 1;
        return 0;
      });

      const doc = new jsPDF();
      const pageWidth = doc.internal.pageSize.getWidth();

      doc.setFontSize(16);
      doc.text(`Participant List - Edition: ${this.selectedEditionName}`, pageWidth / 2, 15, { align: 'center' });

      // تحضير بيانات الجدول مع First Name و Last Name منفصلين
      const data = sortedParticipants.map(p => {
        const parts = p.name.split(' ');
        const firstName = parts[0] || '';
        const lastName = parts[1] || '';
        return [
          firstName,
          lastName,
          p.institution || 'N/A',
          p.phone || 'N/A',
          p.details.total || 'N/A'
        ];
      });

      const headers = [["First Name", "Last Name", "Institution", "Phone", "Total"]];

      autoTable(doc, {
        startY: 25,
        head: headers,
        body: data,
        styles: {
          fontSize: 10,
          cellPadding: 4,
          valign: 'middle',
          halign: 'left',
        },
        headStyles: {
          fillColor: [52, 144, 220],
          textColor: 255,
          fontStyle: 'bold',
        },
        alternateRowStyles: {
          fillColor: [240, 240, 240]
        },
        margin: { left: 15, right: 15 },
        columnStyles: {
          0: { cellWidth: 35 },
          1: { cellWidth: 35 },
          2: { cellWidth: 50 },
          3: { cellWidth: 40 },
          4: { cellWidth: 30 }
        },
      });

      doc.save(`Participant_List_${this.selectedEditionName.replace(/\s+/g, '_')}.pdf`);
    }
  }
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

.edit {
  border: 1px solid #265985;
  color: #265985;
  background: white;
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
  transition: all 0.3s ease-out;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: scale(0.85);
}

/* Modal styles */
.font-poppins {
  font-family: 'Poppins', sans-serif;
}

/* Update and Delete Modal styles to match Programme.vue */
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
.modal-content select {
  width: 95%;
  padding: 10px 14px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  margin-bottom: 12px;
  font-size: 14px;
}

.modal-content input:focus,
.modal-content select:focus {
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

/* Payment Proof Modal retains original styles */
.fixed.inset-0 {
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 50;
  transition: opacity 0.3s ease;
}

.fixed.inset-0 > div {
  background-color: #ffffff;
  padding: 1.5rem;
  border-radius: 0.5rem;
  width: 100%;
  max-width: 48rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  font-family: 'Poppins', sans-serif;
  max-height: 90vh;
  overflow-y: auto;
}

.fixed.inset-0 h3 {
  font-size: 1.25rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 1rem;
  text-align: center;
  position: sticky;
  top: 0;
  background-color: #ffffff;
  z-index: 10;
}

.fixed.inset-0 .flex.justify-end {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
}

/* Responsive */
@media (max-width: 960px) {
  .cards-wrapper {
    justify-content: center;
  }

  .modal-content {
    padding: 20px;
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

  .modal-content {
    padding: 20px;
  }
}
</style>