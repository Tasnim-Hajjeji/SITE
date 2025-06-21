<template>
    <div class="container">
      <h2 class="title">📅 Important Dates</h2>
  
      <button class="btn add" @click="openAddModal">➕ Add Date</button>
  
      <div v-if="importantDates.length > 0" class="dates-list">
        <div v-for="(date, index) in importantDates" :key="index" class="date-card">
          <div class="card-content">
            <h3>{{ date.title_en }} / {{ date.title_fr }}</h3>
            <p>{{ date.description_en }}</p>
            <p>{{ date.description_fr }}</p>
            <p><strong>Date:</strong> {{ date.date }}</p>
            <p><strong>Edition ID:</strong> {{ date.edition_id }}</p>
          </div>
          <div class="actions">
            <button class="btn edit" @click="editDate(index)">✏️</button>
            <button class="btn delete" @click="deleteDate(index)">🗑️</button>
          </div>
        </div>
      </div>
      <p v-else class="empty-msg">No important dates added yet.</p>
  
      <!-- Modal -->
      <transition name="fade">
        <div v-if="showModal" class="modal">
          <div class="modal-content">
            <h3>{{ isEditing ? 'Edit Date' : 'Add Date' }}</h3>
  
            <form @submit.prevent="saveDate">
              <input type="text" v-model="form.title_en" placeholder="Title (EN)" required />
              <input type="text" v-model="form.title_fr" placeholder="Title (FR)" required />
              <textarea v-model="form.description_en" placeholder="Description (EN)" required></textarea>
              <textarea v-model="form.description_fr" placeholder="Description (FR)" required></textarea>
              <input type="date" v-model="form.date" required />
              <input type="number" v-model="form.edition_id" placeholder="Edition ID" required />
  
              <div class="modal-actions">
                <button type="submit" class="btn confirm">{{ isEditing ? 'Update' : 'Add' }}</button>
                <button type="button" class="btn cancel" @click="closeModal">Cancel</button>
              </div>
            </form>
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
        importantDates: [],
        showModal: false,
        isEditing: false,
        editingIndex: null,
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
      deleteDate(index) {
        this.importantDates.splice(index, 1);
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
    /* Container & Titles */
.container {
  padding: 2rem;
  max-width: 1100px;
  margin: auto;
  font-family: 'Poppins', sans-serif;
  animation: fadeInUp 0.6s ease;
}

@keyframes fadeInUp {
  0% {
    opacity: 0;
    transform: translateY(30px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

.title {
  font-size: 1.7rem;
  margin-bottom: 1.5rem;
  color: #1b2d56;
  font-weight: 700;
  position: relative;
}

.title::after {
  content: "";
  width: 80px;
  height: 4px;
  background: #00a6a6;
  display: block;
  margin-top: 8px;
  border-radius: 2px;
}

/* Empty Message */
.empty-msg {
  text-align: center;
  color: #999;
  margin-top: 2rem;
  font-style: italic;
}

/* Buttons */
.btn {
  padding: 8px 16px;
  border-radius: 25px;
  font-weight: 500;
  cursor: pointer;
  border: 1px solid transparent;
  transition: all 0.3s ease;
  font-size: 14px;
}

.btn.add {
  background-color: #fff;
  border-color: #00a6a6;
  color: #00a6a6;
  margin-bottom: 2rem;
}

.btn.add:hover {
  background-color: #00a6a6;
  color: white;
}

.btn.edit {
  background: #fff;
  border-color: #fbbf24;
  color: #fbbf24;
}

.btn.edit:hover {
  background-color: #fbbf24;
  color: white;
}

.btn.delete {
  background: #fff;
  border-color: #ef4444;
  color: #ef4444;
}

.btn.delete:hover {
  background-color: #ef4444;
  color: white;
}

.btn.confirm {
  background: #10b981;
  color: white;
  border: none;
}

.btn.confirm:hover {
  background-color: #059669;
}

.btn.cancel {
  background: #6b7280;
  color: white;
  border: none;
}

.btn.cancel:hover {
  background-color: #4b5563;
}

/* Card Styles */
.dates-list {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.date-card {
  background: #f9fbfc;
  border: 1px solid #cce4f6;
  border-left: 4px solid #00a6a6;
  border-radius: 8px;
  padding: 20px;
  display: flex;
  justify-content: space-between;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
}

.date-card:hover {
  transform: scale(1.01);
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
}

.card-content h3 {
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: #1b2d56;
}

.card-content p {
  margin-bottom: 4px;
  font-size: 14px;
  color: #444;
}

/* Modal Styles */
.modal {
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

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 10px;
}

/* Fade transition */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

/* Responsive */
@media (max-width: 768px) {
  .date-card {
    flex-direction: column;
    gap: 1rem;
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
  