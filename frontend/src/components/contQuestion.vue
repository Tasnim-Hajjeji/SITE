<template>
  <div class="container">
    <!-- Titre H1 ajouté -->
    <h1>Questions du contact</h1>
  </div>

  <!-- Liste des questions -->
  <div class="cards-wrapper">
    <div class="card" v-for="(question) in questions" :key="question.key">
      <div class="card-header">
        <div class="title-date">
          <h3 class="title">{{ question.name }}</h3>
        </div>
        <div class="info">
          <p><strong>Email :</strong> {{ question.email }}</p>
          <p><strong>Sujet :</strong> {{ question.subject }}</p>
          <p><strong>Message :</strong> {{ question.message }}</p>
        </div>
      </div>
      <div class="tools">
        <button class="icon-btn" @click="openDeleteModal(question)">
          <i class="fas fa-trash"></i>
        </button>
      </div>
    </div>
  </div>

  <!-- Modal Delete Confirmation -->
  <transition name="fade">
    <div v-if="showDeleteModal" class="modal-overlay" @click.self="closeDeleteModal">
      <div class="modal-content">
        <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Confirmer Suppression</h3>
        <p class="text-gray-600 mb-4 text-center">Voulez vous supprimer le membre <strong>{{ deleteQuestionData.name }} , {{ deleteQuestionData.subject }}</strong>?</p>
        <div class="modal-actions flex justify-end gap-2 mt-6">
          <button type="button" class="cancel-btn" @click="closeDeleteModal">Cancel</button>
          <button type="button" class="delete-btn" @click="supprimerQuestion" :disabled="isSubmitting">
            {{ isSubmitting ? 'Suppression...' : 'Supprimer' }}
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import ContactService from '@/services/ContactService';
export default {
  data() {
    return {
      showModal: false,
      editIndex: null,
      form: {
        nom: '',
        email: '',
        sujet: '',
        message: ''
      },
      questions: [],
      showDeleteModal:false,
      deleteQuestionData:null,
      isSubmitting: false,
    };
  },
  async created() {
    await this.fetchQuestions();
    console.log('the questions', this.questions);
  },
  methods: {
    async fetchQuestions() {
      try {
        const response = await ContactService.getContacts();
        console.log("the response", response);
        this.questions = response;
        console.log('questions in fetch', this.questions)
      } catch (error) {
        console.error("error fetching contacts", error);
      }
    },
    enregistrerQuestion() {
      if (this.editIndex !== null) {
        this.questions.splice(this.editIndex, 1, { ...this.form });
      } else {
        this.questions.push({ ...this.form });
      }
      this.reinitialiserFormulaire();
    },
    openDeleteModal(question) {
      this.deleteQuestionData = question;
      this.showDeleteModal = true;
    },
    closeDeleteModal() {
      this.showDeleteModal = false;
      this.deleteQuestionData = null;
    },
    async supprimerQuestion() {
      this.isSubmitting = true;
      try {
        await ContactService.deleteContact(this.deleteQuestionData.id)
        this.closeDeleteModal();
        this.fetchQuestions();
      }catch (error) {
        console.error("Error deleting contact:", error);
      } finally {
        this.isSubmitting = false;
      }
    },
    reinitialiserFormulaire() {
      this.showModal = false;
      this.editIndex = null;
      this.form = {
        nom: '',
        email: '',
        sujet: '',
        message: ''
      };
    },
    toggleEditionDropdown() {
      this.editionDropdownOpen = !this.editionDropdownOpen;
    },
    selectEdition(item) {
      alert(`Édition sélectionnée : ${item.name}`);
      this.editionDropdownOpen = false;
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
  padding: 0.5rem 1rem;
  border-radius: 8px;
  font-weight: 600;
  font-size: 0.9rem;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
}

.edit:hover {
  background: #e6f0fc;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(38, 89, 133, 0.3);
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

.info {
  word-wrap: break-word;
  overflow-wrap: break-word;
  word-break: break-all;
}

.info p {
  font-size: 14px;
  margin: 4px 0;
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
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 10px;
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

/* Dropdown menu styles */
.dropdown-menu {
  position: absolute;
  top: 60px;
  /* un peu en dessous du bouton Edition */
  right: 0;
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
</style>