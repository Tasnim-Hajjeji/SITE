```vue
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
  background-color: #265985;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 5px;
  border: none;
}
.btn.add:hover {
  background-color: #1e476b;
}
.btn.cancel {
  background: #999;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 5px;
  border: none;
}

/* Button styles for Delete modal */
.btn.delete {
  background: #eb5a5a;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 5px;
  border: none;
}

/* Update and Delete button styles from Programme.vue */
.small-btn {
  padding: 4px 8px;
  font-size: 0.8rem;
  border-radius: 6px;
  border: 1px solid;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}
.update-btn {
  border-color: #265985;
  color: #265985;
  background-color: #fff;
}
.update-btn:hover {
  background-color: #265985;
  color: #fff;
}
.delete-btn {
  border-color: #e53935;
  color: #e53935;
  background-color: #fff;
}
.delete-btn:hover {
  background-color: #e53935;
  color: #fff;
}

/* Fade transition styles */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.loading {
  text-align: center;
  padding: 2rem;
  font-style: italic;
  color: #666;
}

.error-message {
  padding: 1rem;
  background-color: #ffebee;
  color: #c62828;
  border-radius: 4px;
  margin-bottom: 1rem;
}

/* Poppins font */
.font-poppins {
  font-family: 'Poppins', sans-serif;
}
</style>
```