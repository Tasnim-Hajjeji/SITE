<template>
  <section class="keynote-section">
    <div class="header-row">
      <h2 class="title">Keynote Session</h2>
      <div class="action-buttons">
        <button class="add-btn" @click="showModal = true">
          <i class="fas fa-plus"></i> Add
        </button>
      </div>
    </div>

    <div v-if="isLoading" class="loading-message">
      Loading keynotes...
    </div>

    <div v-else-if="items.length === 0" class="empty-message">
      No keynotes available.
    </div>
    <div v-else class="carousel">
      <button class="nav-btn" @click="prevSlide" :disabled="currentIndex === 0">
        <i class="fas fa-arrow-left"></i>
      </button>

      <div class="slides-wrapper">
        <div class="slide" v-for="(item, index) in visibleSlides" :key="index">
          <div class="action-icons">
            <i class="fas fa-edit update-icon" @click="openUpdateModal(item, index)"></i>
            <i class="fas fa-trash delete-icon" @click="deleteKeynote(item.id)"></i>
          </div>
          <div class="quote-icon">❝</div>
          <p class="text">{{ currentLanguage === 'fr' ? item.description_fr : item.description_en }}</p>
          <div class="profile">
            <img :src="getImageUrl(item.image_url) || 'https://i.pravatar.cc/50'" alt="Speaker photo" class="avatar" />
            <div>
              <h3>{{ item.speaker_name }}</h3>
              <p class="role">{{ item.speaker_role }}</p>
            </div>
          </div>
        </div>
      </div>

      <button class="nav-btn" @click="nextSlide" :disabled="currentIndex === items.length - 1">
        <i class="fas fa-arrow-right"></i>
      </button>
    </div>

    <div class="dots">
      <span v-for="(dot, i) in items.length" :key="i" :class="['dot', { active: i === currentIndex }]"></span>
    </div>

    <!-- Add New Keynote Modal -->
    <div v-if="showModal"
      class="modal-overlay">
      <div class="modal-content">
        <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Add New Keynote</h3>
        <form @submit.prevent="addKeynote" class="space-y-0">
          <div>
            <label for="description_fr" class="block mb-1 text-xs text-gray-500 font-medium">Quote (French)</label>
            <textarea v-model="newKeynote.description_fr" id="description_fr"
              placeholder="Enter the French keynote quote here..."
              class="w-[95%] p-2 border border-gray-300 rounded-lg" rows="4" required></textarea>
          </div>
          <div>
            <label for="description_en" class="block mb-1 text-xs text-gray-500 font-medium">Quote (English)</label>
            <textarea v-model="newKeynote.description_en" id="description_en"
              placeholder="Enter the English keynote quote here..."
              class="w-[95%] p-2 border border-gray-300 rounded-lg" rows="4" required></textarea>
          </div>
          <div>
            <label for="speaker_name" class="block mb-1 text-xs text-gray-500 font-medium">Speaker Name</label>
            <input v-model="newKeynote.speaker_name" id="speaker_name" type="text" placeholder="Enter speaker name"
              class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
          </div>
          <div>
            <label for="speaker_role" class="block mb-1 text-xs text-gray-500 font-medium">Role</label>
            <input v-model="newKeynote.speaker_role" id="speaker_role" type="text"
              placeholder="Enter speaker's role or title"
              class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
          </div>
          <!-- <div>
            <label for="edition_id" class="block mb-1 text-xs text-gray-500 font-medium">Edition ID</label>
            <input v-model="newKeynote.edition_id" id="edition_id" type="text" placeholder="Enter edition ID"
              class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
          </div> -->
          <div>
            <label for="image" class="block mb-1 text-xs text-gray-500 font-medium">Profile Image</label>
            <input id="image" type="file" @change="handleImageUpload" accept="image/*"
              class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
            <div v-if="imagePreview" class="mt-2">
              <img :src="imagePreview" alt="Image Preview" class="w-20 h-20 object-cover rounded-md" />
            </div>
          </div>
          <div v-if="error" class="p-2 bg-red-100 text-red-700 rounded-md flex items-center">
            <span class="mr-1">!</span> {{ error }}
          </div>
          <div class="modal-actions flex justify-end gap-2 mt-6">
            <button type="button"
              class="cancel-btn bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 font-semibold rounded-lg px-4 py-1.5 hover:from-gray-300 hover:to-gray-400 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out"
              @click="showModal = false">
              Cancel
            </button>
            <button type="submit"
              class="add-btn bg-gradient-to-r from-blue-800 to-blue-600 text-white font-semibold rounded-lg px-4 py-1.5 hover:from-blue-900 hover:to-blue-700 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out">
              Add Keynote
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Update Keynote Modal -->
    <div v-if="showUpdateModal"
      class="modal-overlay">
      <div class="modal-content">
        <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Update Keynote</h3>
        <form @submit.prevent="updateKeynote" class="space-y-0">
          <div>
            <label for="update_description_fr" class="block mb-1 text-xs text-gray-500 font-medium">Quote (French)</label>
            <textarea v-model="selectedItem.description_fr" id="update_description_fr"
              placeholder="Enter the French keynote quote here..."
              class="w-[95%] p-2 border border-gray-300 rounded-lg" rows="4" required></textarea>
          </div>
          <div>
            <label for="update_description_en" class="block mb-1 text-xs text-gray-500 font-medium">Quote (English)</label>
            <textarea v-model="selectedItem.description_en" id="update_description_en"
              placeholder="Enter the English keynote quote here..."
              class="w-[95%] p-2 border border-gray-300 rounded-lg" rows="4" required></textarea>
          </div>
          <div>
            <label for="update_speaker_name" class="block mb-1 text-xs text-gray-500 font-medium">Speaker Name</label>
            <input v-model="selectedItem.speaker_name" id="update_speaker_name" type="text"
              placeholder="Enter speaker name"
              class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
          </div>
          <div>
            <label for="update_speaker_role" class="block mb-1 text-xs text-gray-500 font-medium">Role</label>
            <input v-model="selectedItem.speaker_role" id="update_speaker_role" type="text"
              placeholder="Enter speaker's role or title"
              class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
          </div>
          <div>
            <label for="update_edition_id" class="block mb-1 text-xs text-gray-500 font-medium">Edition ID</label>
            <input v-model="selectedItem.edition_id" id="update_edition_id" type="text" placeholder="Enter edition ID"
              class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
          </div>
          <div>
            <label for="update_image" class="block mb-1 text-xs text-gray-500 font-medium">Profile Image</label>
            <input id="update_image" type="file" @change="handleUpdateImageUpload" accept="image/*"
              class="w-[95%] p-2 border border-gray-300 rounded-lg" />
            <div v-if="updateImagePreview" class="mt-2">
              <img :src="updateImagePreview" alt="Image Preview" class="w-20 h-20 object-cover rounded-md" />
            </div>
          </div>
          <div v-if="error" class="p-2 bg-red-100 text-red-700 rounded-md flex items-center">
            <span class="mr-1">!</span> {{ error }}
          </div>
          <div class="modal-actions flex justify-end gap-2 mt-6">
            <button type="button"
              class="cancel-btn bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 font-semibold rounded-lg px-4 py-1.5 hover:from-gray-300 hover:to-gray-400 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out"
              @click="showUpdateModal = false">
              Cancel
            </button>
            <button type="submit"
              class="add-btn bg-gradient-to-r from-blue-800 to-blue-600 text-white font-semibold rounded-lg px-4 py-1.5 hover:from-blue-900 hover:to-blue-700 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out">
              Update Keynote
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import KeynoteService from '@/services/KeynoteService'
import { useRoute } from 'vue-router'

const route = useRoute()
const currentIndex = ref(0)
const showModal = ref(false)
const showUpdateModal = ref(false)
const newKeynote = ref({
  description_fr: '',
  description_en: '',
  speaker_name: '',
  speaker_role: '',
  edition_id: localStorage.getItem('selectedEditionId') || route.params.editionId,
  image: null,
})
const imagePreview = ref(null)
const selectedItem = ref(null)
const updateImagePreview = ref(null)
const error = ref(null)
const items = ref([])
const isLoading = ref(true)
const currentLanguage = ref('en') // Default to English

// Fetch keynotes on component mount
onMounted(async () => {
  try {
    isLoading.value = true
    const response = await KeynoteService.getKeynotesByEdition(localStorage.getItem('selectedEditionId') || route.params.editionId)
    console.log('Fetched keynotes:', response.data) // Debugging
    items.value = response.data
  } catch (err) {
    error.value = 'Failed to load keynotes: ' + err.message
  } finally {
    isLoading.value = false
  }
})

const visibleSlides = computed(() => {
  const currentItem = items.value[currentIndex.value]
  return currentItem ? [currentItem] : []
})
function nextSlide() {
  if (currentIndex.value < items.value.length - 1) currentIndex.value++
}

function prevSlide() {
  if (currentIndex.value > 0) currentIndex.value--
}
function getImageUrl(imagePath) {
  return `http://localhost:8000/storage/${imagePath}`;
}

function handleImageUpload(event) {
  const file = event.target.files[0]
  if (file) {
    console.log('Selected file:', file) // Debugging
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreview.value = e.target.result
    }
    reader.readAsDataURL(file)
    newKeynote.value.image = file
  } else {
    console.error('No file selected') // Debugging
  }
}
function handleUpdateImageUpload(event) {
  const file = event.target.files[0]
  if (file) {
    const reader = new FileReader()
    reader.onload = (e) => {
      updateImagePreview.value = e.target.result
    }
    reader.readAsDataURL(file)
    selectedItem.value.image = file
  } else {
    updateImagePreview.value = getImageUrl(selectedItem.value.image_url) // Keep original if no new file
  }
}

async function addKeynote() {
  try {
    if (!newKeynote.value.description_fr || !newKeynote.value.description_en ||
      !newKeynote.value.speaker_name || !newKeynote.value.speaker_role ||
      !newKeynote.value.edition_id || !newKeynote.value.image) {
      error.value = 'Please fill all required fields'
      return
    }
    console.log('Edition ID:', newKeynote.value.edition_id) // Should show a valid ID
    const formData = new FormData()
    formData.append('description_fr', newKeynote.value.description_fr)
    formData.append('description_en', newKeynote.value.description_en)
    formData.append('speaker_name', newKeynote.value.speaker_name)
    formData.append('speaker_role', newKeynote.value.speaker_role)
    formData.append('edition_id', newKeynote.value.edition_id)
    formData.append('image', newKeynote.value.image) // Make sure this is the File object

    console.log('FormData entries:') // Debugging
    for (let [key, value] of formData.entries()) {
      console.log(key, value)
    }

    const response = await KeynoteService.createKeynote(formData)
    items.value.push(response.data)

    // Reset form
    newKeynote.value = {
      description_fr: '',
      description_en: '',
      speaker_name: '',
      speaker_role: '',
      edition_id: localStorage.getItem('selectedEditionId') || route.params.editionId,
      image: null
    }
    imagePreview.value = null
    error.value = null
    showModal.value = false
  } catch (err) {
    console.error('Full error:', err) // Debugging
    console.error('Response:', err.response) // Debugging
    error.value = 'Error creating keynote: ' +
      (err.response?.data?.error || err.message)
  }
}

function openUpdateModal(item, index) {
  selectedItem.value = { ...item, index }
  updateImagePreview.value = getImageUrl(item.image_url)
  showUpdateModal.value = true
}

async function updateKeynote() {
  try {
    if (!selectedItem.value.description_fr || !selectedItem.value.description_en ||
      !selectedItem.value.speaker_name || !selectedItem.value.speaker_role) {
      error.value = 'Please fill all required fields'
      return
    }

    const formData = new FormData()
    formData.append('description_fr', selectedItem.value.description_fr)
    formData.append('description_en', selectedItem.value.description_en)
    formData.append('speaker_name', selectedItem.value.speaker_name)
    formData.append('speaker_role', selectedItem.value.speaker_role)
    formData.append('edition_id', selectedItem.value.edition_id) // Fixed: use selectedItem instead of newKeynote
    
    // Only append image if a new one was selected
    if (selectedItem.value.image && selectedItem.value.image instanceof File) {
      formData.append('image', selectedItem.value.image)
    }

    console.log('FormData entries:') // Debugging
    for (let [key, value] of formData.entries()) {
      console.log(key, value)
    }
    
    const response = await KeynoteService.updateKeynote(selectedItem.value.id, formData)
    console.log('Update response:', response.data) // Debugging
    
    // Update the item in the array
    const itemIndex = items.value.findIndex(item => item.id === selectedItem.value.id)
    if (itemIndex !== -1) {
      items.value[itemIndex] = response.data.keynote
    }

    showUpdateModal.value = false
    error.value = null
    updateImagePreview.value = null // Clear the preview
  } catch (err) {
    console.error('Update error:', err)
    error.value = 'Error updating keynote: ' + (err.response?.data?.error || err.message)
  }
}

async function deleteKeynote(id) {
  try {
    if (confirm('Are you sure you want to delete this keynote?')) {
      await KeynoteService.deleteKeynote(id)
      items.value = items.value.filter(item => item.id !== id)
      if (currentIndex.value >= items.value.length) {
        currentIndex.value = Math.max(0, items.value.length - 1)
      }
    }
  } catch (err) {
    error.value = 'Error deleting keynote: ' + err.message
  }
}
</script>

<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css");

.keynote-section {
  padding: 2rem;
  max-width: 1200px;
  margin: auto;
  font-family: 'Segoe UI', sans-serif;
  animation: fadeInUp 0.6s ease;
}

@keyframes fadeInUp {
  0% {
    opacity: 0;
    transform: translateY(40px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

.header-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
  flex-wrap: wrap;
  gap: 1rem;
}

.title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1b2d56;
  margin: 0;
  position: relative;
}

.title::after {
  content: "";
  width: 100px;
  height: 4px;
  background: #265985;
  display: block;
  margin-top: 8px;
  border-radius: 2px;
}

/* Buttons */
.action-buttons button {
  padding: 8px 16px;
  border-radius: 25px;
  border: 1px solid;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.3s ease;
}

.add-btn {
  border-color: #265985;
  color: #265985;
  background-color: white;
}

.add-btn:hover {
  background-color: #265985;
  color: white;
}

/* Carousel and Slide Styles (unchanged) */
.carousel {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1rem;
  flex-wrap: nowrap;
  transition: all 0.5s ease;
}

.nav-btn {
  border: 2px solid #1c1c3c;
  background: white;
  color: #1c1c3c;
  border-radius: 50%;
  width: 48px;
  height: 48px;
  font-size: 1.2rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}

.nav-btn:hover {
  background-color: #f6f6f6;
  transform: scale(1.1);
}

.nav-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.slides-wrapper {
  display: flex;
  justify-content: center;
  width: 100%;
  max-width: 600px;
  position: relative;
}

.slide {
  background: white;
  border-radius: 20px;
  box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.1);
  padding: 2rem;
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  animation: slideIn 0.4s ease;
  transition: transform 0.3s ease;
  position: relative;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateX(30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.action-icons {
  position: absolute;
  top: 15px;
  right: 15px;
  display: flex;
  gap: 10px;
  z-index: 10;
}

.update-icon,
.delete-icon {
  cursor: pointer;
  font-size: 1rem;
  padding: 6px;
  border-radius: 50%;
  background-color: #f2f2f2;
  color: #333;
  transition: all 0.3s ease;
}

.update-icon:hover {
  background-color: #265985;
  color: white;
}

.delete-icon:hover {
  background-color: #e53935;
  color: white;
}

.quote-icon {
  font-size: 2rem;
  color: #f6b10c;
  margin-bottom: 1rem;
  transform: rotate(-10deg);
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0% {
    transform: rotate(-10deg) scale(1);
  }
  50% {
    transform: rotate(-10deg) scale(1.05);
  }
  100% {
    transform: rotate(-10deg) scale(1);
  }
}

.text {
  font-size: 0.95rem;
  color: #333;
  flex-grow: 1;
  margin-bottom: 1rem;
  line-height: 1.6;
  transition: color 0.3s ease;
}

.profile {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.avatar {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.avatar:hover {
  transform: scale(1.1);
}

.role {
  font-size: 0.85rem;
  color: gray;
}

.dots {
  display: flex;
  justify-content: center;
  gap: 0.5rem;
  margin-top: 1rem;
}

.dot {
  width: 10px;
  height: 10px;
  background: #c4d6d6;
  border-radius: 50%;
  transition: all 0.3s ease;
}

.dot.active {
  background: #00a6a6;
  width: 12px;
  height: 12px;
  animation: bounce 0.4s ease;
}

@keyframes bounce {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.4);
  }
  100% {
    transform: scale(1);
  }
}

.loading-message {
  text-align: center;
  padding: 2rem;
  font-style: italic;
  color: #666;
}

.empty-message {
  text-align: center;
  color: #999;
  margin-top: 2rem;
  font-style: italic;
}

/* Modal Styles */
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

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 10px;
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

/* Responsive */
@media (max-width: 768px) {
  .nav-btn {
    width: 57px;
    height: 45px;
  }

  .header-row {
    flex-direction: column;
    align-items: flex-start;
  }

  .action-buttons {
    width: 100%;
  }

  .action-buttons button {
    width: 100%;
  }
}
</style>