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
  /* General */
  .container {
    max-width: 900px;
    margin: auto;
    padding: 30px;
    font-family: 'Segoe UI', sans-serif;
    color: #333;
  }
  
  .title {
    font-size: 2rem;
    margin-bottom: 20px;
    color: #2563eb;
    font-weight: bold;
  }
  
  .empty-msg {
    text-align: center;
    color: #777;
    margin-top: 20px;
  }
  
  /* Buttons */
  .btn {
    border: none;
    border-radius: 6px;
    padding: 8px 14px;
    font-weight: 600;
    transition: background 0.3s, transform 0.2s;
    cursor: pointer;
  }
  
  .btn.add {
    background-color: #2563eb;
    color: white;
    margin-bottom: 20px;
  }
  
  .btn.add:hover {
    background-color: #1d4ed8;
    transform: scale(1.02);
  }
  
  .btn.edit {
    background-color: #facc15;
    color: #000;
  }
  
  .btn.edit:hover {
    background-color: #eab308;
  }
  
  .btn.delete {
    background-color: #ef4444;
    color: white;
  }
  
  .btn.delete:hover {
    background-color: #dc2626;
  }
  
  .btn.confirm {
    background-color: #10b981;
    color: white;
  }
  
  .btn.confirm:hover {
    background-color: #059669;
  }
  
  .btn.cancel {
    background-color: #6b7280;
    color: white;
  }
  
  .btn.cancel:hover {
    background-color: #4b5563;
  }
  
  /* Cards */
  .dates-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }
  
  .date-card {
    background: #f1f5f9;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: start;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    transition: box-shadow 0.3s;
  }
  
  .date-card:hover {
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  }
  
  .card-content h3 {
    margin: 0 0 8px;
    color: #1f2937;
  }
  
  /* Modal */
  .modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(30, 41, 59, 0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 999;
  }
  
  .modal-content {
    background: white;
    padding: 30px;
    border-radius: 12px;
    width: 90%;
    max-width: 550px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    animation: zoomIn 0.3s ease-out;
  }
  
  .modal-content input,
  .modal-content textarea {
    width: 100%;
    margin-bottom: 15px;
    padding: 10px 12px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 1rem;
  }
  
  .modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 10px;
  }
  
  /* Transitions */
  .fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
  }
  .fade-enter-from, .fade-leave-to {
    opacity: 0;
  }
  
  @keyframes zoomIn {
    from {
      transform: scale(0.8);
      opacity: 0;
    }
    to {
      transform: scale(1);
      opacity: 1;
    }
  }
  </style>
  