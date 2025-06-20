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
    <div class="edition-card" v-for="edition in editions" :key="edition.year">
      <img class="edition-image" :src="edition.image" alt="Edition image" />

      <div class="edition-info">
        <div class="title-status">
          <h3>Site {{ edition.year }}</h3>
          <span class="status">In Progress</span>
        </div>

        <div class="details">
          <div class="detail">
            <i class="icon">📍</i>
            <div>
              <strong>Location</strong>
              <p>{{ edition.place }}</p>
            </div>
          </div>
          <div class="detail">
            <i class="icon">🕒</i>
            <div>
              <strong>Start Date</strong>
              <p>{{ edition.start_date }}</p>
            </div>
          </div>
        </div>
      </div>
      <button class="voir-plus" @click="selectEdition(edition.year)">See More</button>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="modal-overlay" @click.self="showModal = false">
      <div class="modal-content">
        <h3>Create New Edition</h3>
        <form @submit.prevent="submitEdition">
          <label>
            Edition Name:
            <input
              type="text"
              name="name"
              v-model="form.name"
              required
            />
          </label>

          <label>
            Description (English):
            <textarea
              rows="2"
              name="description_en"
              v-model="form.description_en"
              required
            ></textarea>
          </label>

          <label>
            Description (French):
            <textarea
              rows="2"
              name="description_fr"
              v-model="form.description_fr"
              required
            ></textarea>
          </label>

          <label>
            Location:
            <input
              type="text"
              name="place"
              v-model="form.place"
              required
            />
          </label>

          <label>
            Start Date:
            <input
              type="date"
              name="start_date"
              v-model="form.start_date"
              required
            />
          </label>

          <label>
            End Date:
            <input
              type="date"
              name="end_date"
              v-model="form.end_date"
              required
            />
          </label>

          <div class="modal-actions">
            <button type="submit">Save</button>
            <button type="button" @click="showModal = false">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const showModal = ref(false)

const form = ref({
  name: '',
  description_en: '',
  description_fr: '',
  place: '',
  start_date: '',
  end_date: ''
})

const editions = ref([
  { year: 2025, image: require('@/assets/logosite.png') },
  { year: 2024, image: require('@/assets/logosite.png') },
  { year: 2023, image: require('@/assets/logosite.png') },
  { year: 2022, image: require('@/assets/logosite.png') },
])

const selectEdition = (editionId) => {
  localStorage.setItem('selectedEditionId', editionId.toString())
  router.push('/admin/edition')
}

const submitEdition = async () => {
  try {
    const response = await fetch('http://localhost:8000/api/editions', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(form.value),
    })

    if (!response.ok) throw new Error('Failed to create edition.')

    await response.json()

    editions.value.unshift({
      year: new Date(form.value.start_date).getFullYear(),
      image: require('@/assets/logosite.png'),
      place: form.value.place,
      start_date: form.value.start_date
    })

    form.value = {
      name: '',
      description_en: '',
      description_fr: '',
      place: '',
      start_date: '',
      end_date: ''
    }

    showModal.value = false
  } catch (error) {
    console.error('Error:', error)
  }
}
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.4);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  animation: fadeInOverlay 0.3s ease;
}
.modal-content {
  background: white;
  border-radius: 15px;
  padding: 2rem;
  width: 90%;
  max-width: 500px;
  animation: scaleIn 0.3s ease;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
}
.modal-content h3 {
  margin-bottom: 1rem;
  color: #265985;
}
.modal-content label {
  display: block;
  margin-bottom: 1rem;
  font-weight: 500;
}
.modal-content input,
.modal-content textarea {
  width: 100%;
  padding: 0.5rem;
  margin-top: 0.3rem;
  border-radius: 8px;
  border: 1px solid #ccc;
  font-size: 1rem;
}
.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  margin-top: 1.5rem;
}
.modal-actions button {
  padding: 0.6rem 1.2rem;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: bold;
}
.modal-actions button[type="submit"] {
  background: #265985;
  color: white;
}
.modal-actions button[type="button"] {
  background: #ccc;
  color: black;
}
@keyframes scaleIn {
  from {
    transform: scale(0.8);
    opacity: 0;
  }
  to {
    transform: scale(1);
    opacity: 1;
  }
}
@keyframes fadeInOverlay {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

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
