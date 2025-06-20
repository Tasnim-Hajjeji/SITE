<template>
  <section class="editions-section">
    <!-- Header -->
    <div class="header">
      <h2>Editions</h2>
      <button class="new-edition-btn" @click="showModal = true">
        <span>➕</span> New Edition
      </button>
    </div>

    <!-- Edition Cards -->
    <div class="edition-card" v-for="edition in editions" :key="edition.name">
      <img class="edition-image" :src="edition.images_url" alt="Edition image" />
      <div class="edition-info">
        <div class="title-status">
          <h3>{{ edition.name }}</h3>
          <span class="status">En Cours</span>
        </div>
        <div class="details">
          <div class="detail">
            <i class="icon">📍</i>
            <div>
              <strong>Place</strong>
              <p>{{ edition.place }}</p>
            </div>
          </div>
          <div class="detail">
            <i class="icon">🕒</i>
            <div>
              <strong>Date</strong>
              <p>{{ edition.start_date }} → {{ edition.end_date }}</p>
            </div>
          </div>
        </div>
      </div>
      <button class="voir-plus" @click="selectEdition(edition.name)">Voir plus</button>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="modal-overlay">
      <div class="modal">
        <h2>New Edition</h2>
        <form @submit.prevent="addEdition">
          <input type="text" v-model="newEdition.name" name="name" placeholder="Name" required />
          <textarea v-model="newEdition.description_fr" name="description_fr" placeholder="Description FR" required></textarea>
          <textarea v-model="newEdition.description_en" name="description_en" placeholder="Description EN" required></textarea>
          <input type="date" v-model="newEdition.start_date" name="start_date" required />
          <input type="date" v-model="newEdition.end_date" name="end_date" required />
          <input type="text" v-model="newEdition.place" name="place" placeholder="Place" required />

          <!-- Input fichier image -->
          <input
            type="file"
            accept="image/*"
            @change="onImageSelected"
            required
          />

          <div class="modal-actions">
            <button type="submit" class="add-btn">Add</button>
            <button type="button" class="cancel-btn" @click="closeModal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </section>
</template>

<script setup>
/* eslint-disable no-undef */
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const emit = defineEmits(['editionSelected'])
const router = useRouter()
const showModal = ref(false)

const editions = ref([
  {
    name: 'SITE 2025',
    description_fr: 'Description en français',
    description_en: 'Description in English',
    start_date: '2025-10-24',
    end_date: '2025-10-26',
    place: 'Hammamet',
    images_url: require('@/assets/logosite.png'),
  },
  {
    name: 'SITE 2024',
    description_fr: 'Description en français',
    description_en: 'Description in English',
    start_date: '2024-10-20',
    end_date: '2024-10-22',
    place: 'Hammamet',
    images_url: require('@/assets/logosite.png'),
  },
])

const newEdition = ref({
  name: '',
  description_fr: '',
  description_en: '',
  start_date: '',
  end_date: '',
  place: '',
  images_url: '', // stockera data URL base64 de l’image
})

const selectEdition = (editionId) => {
  localStorage.setItem('selectedEditionId', editionId)
  emit('editionSelected', editionId)
  router.push('/admin/edition')
}

const onImageSelected = (event) => {
  const file = event.target.files[0]
  if (!file) return

  const reader = new FileReader()
  reader.onload = (e) => {
    newEdition.value.images_url = e.target.result // data URL base64
  }
  reader.readAsDataURL(file)
}

const addEdition = () => {
  editions.value.unshift({ ...newEdition.value })
  closeModal()
}

const closeModal = () => {
  showModal.value = false
  Object.keys(newEdition.value).forEach(key => (newEdition.value[key] = ''))
}
</script>

<style scoped>
.editions-section {
  font-family: 'Segoe UI', sans-serif;
  width: 100%;
  max-width: 1200px;
  margin: auto;
  padding: 1rem;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 1rem;
}

.header h2 {
  font-size: 1.7rem;
  color: #265985;
}

.new-edition-btn {
  border: 1px solid #000;
  background: white;
  padding: 0.5rem 1rem;
  border-radius: 999px;
  font-weight: 500;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.3s ease;
}

.new-edition-btn:hover {
  border-color: #265985;
  color: #265985;
  background-color: #fff;
  transform: scale(1.05);
}

.edition-card {
  display: flex;
  flex-wrap: wrap;
  align-items: flex-start;
  background: white;
  border-radius: 15px;
  margin-top: 1.5rem;
  padding: 1rem;
  box-shadow: 0 9px 40px rgba(0, 0, 0, 0.05);
  gap: 1rem;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  animation: fadeInUp 0.6s ease;
}

.edition-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 50px rgba(0, 0, 0, 0.1);
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.edition-image {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 10px;
  flex-shrink: 0;
  transition: transform 0.3s ease;
}

.edition-card:hover .edition-image {
  transform: rotate(2deg) scale(1.05);
}

.edition-info {
  flex: 1;
  min-width: 220px;
}

.title-status {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 1rem;
  margin-bottom: 0.5rem;
}

.title-status h3 {
  color: #265985;
  font-size: 1.2rem;
}

.status {
  border: 1px solid #265985;
  border-radius: 999px;
  padding: 0.2rem 0.8rem;
  font-size: 0.8rem;
  color: #265985;
}

.details {
  display: flex;
  flex-wrap: wrap;
  gap: 2rem;
}

.detail {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #666;
}

.detail strong {
  color: #3c3c9c;
  font-size: 0.9rem;
}

.icon {
  font-size: 1.3rem;
  color: #6a0dad;
}

.voir-plus {
  background: #265985;
  color: white;
  border: none;
  border-radius: 10px;
  padding: 0.7rem 1.2rem;
  font-weight: bold;
  cursor: pointer;
  margin-left: auto;
  margin-top: 1rem;
  transition: background 0.3s ease, transform 0.3s ease;
}

.voir-plus:hover {
  background: #1e4b6b;
  transform: translateY(-2px);
}

/* === MODAL === */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 999;
}

.modal {
  background: white;
  padding: 2rem;
  border-radius: 15px;
  width: 90%;
  max-width: 500px;
  box-shadow: 0 9px 40px rgba(0, 0, 0, 0.1);
  animation: fadeInUp 0.4s ease;
}

.modal h2 {
  margin-bottom: 1rem;
  color: #265985;
}

.modal input,
.modal textarea {
  width: 100%;
  margin-bottom: 1rem;
  padding: 0.6rem;
  border: 1px solid #ccc;
  border-radius: 10px;
  font-size: 1rem;
}

.modal textarea {
  resize: vertical;
  height: 80px;
}

.modal-actions {
  display: flex;
  justify-content: space-between;
  gap: 1rem;
}

.add-btn {
  background: #265985;
  color: white;
  border: none;
  padding: 0.6rem 1rem;
  border-radius: 10px;
  cursor: pointer;
}

.cancel-btn {
  background: #ccc;
  color: #333;
  border: none;
  padding: 0.6rem 1rem;
  border-radius: 10px;
  cursor: pointer;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .edition-card {
    flex-direction: column;
    align-items: flex-start;
  }

  .voir-plus {
    width: 100%;
  }

  .details {
    flex-direction: column;
    gap: 1rem;
  }

  .header h2 {
    font-size: 1.5rem;
  }

  .new-edition-btn {
    width: 100%;
    justify-content: center;
  }
}
</style>
