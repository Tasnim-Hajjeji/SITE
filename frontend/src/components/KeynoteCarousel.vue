<template>
  <section class="keynote-section">
    <h2 class="title">Keynote Session</h2>

    <div class="action-buttons">
      <button class="add-btn" @click="showModal = true">
        <i class="fas fa-plus"></i> Add
      </button>
    </div><br><br>
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
            <img :src="getImageUrl(item.image_url )|| 'https://i.pravatar.cc/50'" alt="Speaker photo" class="avatar" />
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
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
      <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-md font-poppins max-h-[90vh] overflow-y-auto">
        <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">Add New Keynote</h3>
        <div class="space-y-4">
          <div>
            <label for="description_fr" class="block text-sm font-medium text-gray-700">Quote (French)</label>
            <textarea v-model="newKeynote.description_fr" id="description_fr"
              placeholder="Enter the French keynote quote here..."
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              rows="4" required></textarea>
          </div>
          <div>
            <label for="description_en" class="block text-sm font-medium text-gray-700">Quote (English)</label>
            <textarea v-model="newKeynote.description_en" id="description_en"
              placeholder="Enter the English keynote quote here..."
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              rows="4" required></textarea>
          </div>
          <div>
            <label for="speaker_name" class="block text-sm font-medium text-gray-700">Speaker Name</label>
            <input v-model="newKeynote.speaker_name" id="speaker_name" type="text" placeholder="Enter speaker name"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required />
          </div>
          <div>
            <label for="speaker_role" class="block text-sm font-medium text-gray-700">Role</label>
            <input v-model="newKeynote.speaker_role" id="speaker_role" type="text"
              placeholder="Enter speaker's role or title"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required />
          </div>
          <!-- <div>
            <label for="edition_id" class="block text-sm font-medium text-gray-700">Edition ID</label>
            <input v-model="newKeynote.edition_id" id="edition_id" type="text" placeholder="Enter edition ID"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required />
          </div> -->
          <div>
            <label for="image" class="block text-sm font-medium text-gray-700">Profile Image</label>
            <input id="image" type="file" @change="handleImageUpload" accept="image/*"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required />
            <div v-if="imagePreview" class="mt-2">
              <img :src="imagePreview" alt="Image Preview" class="w-20 h-20 object-cover rounded-md" />
            </div>
          </div>
          <div v-if="error" class="p-2 bg-red-100 text-red-700 rounded-md flex items-center">
            <span class="mr-1">!</span> {{ error }}
          </div>
        </div>
        <div class="mt-6 flex justify-end space-x-3">
          <button @click="showModal = false" type="button"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200">
            Cancel
          </button>
          <button @click="addKeynote" type="button"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
            Add Keynote
          </button>
        </div>
      </div>
    </div>

    <!-- Update Keynote Modal -->
    <div v-if="showUpdateModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
      <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-md font-poppins max-h-[90vh] overflow-y-auto">
        <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">Update Keynote</h3>
        <div class="space-y-4">
          <div>
            <label for="update_description_fr" class="block text-sm font-medium text-gray-700">Quote (French)</label>
            <textarea v-model="selectedItem.description_fr" id="update_description_fr"
              placeholder="Enter the French keynote quote here..."
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              rows="4" required></textarea>
          </div>
          <div>
            <label for="update_description_en" class="block text-sm font-medium text-gray-700">Quote (English)</label>
            <textarea v-model="selectedItem.description_en" id="update_description_en"
              placeholder="Enter the English keynote quote here..."
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              rows="4" required></textarea>
          </div>
          <div>
            <label for="update_speaker_name" class="block text-sm font-medium text-gray-700">Speaker Name</label>
            <input v-model="selectedItem.speaker_name" id="update_speaker_name" type="text"
              placeholder="Enter speaker ID"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required />
          </div>
          <div>
            <label for="update_speaker_role" class="block text-sm font-medium text-gray-700">Role</label>
            <input v-model="selectedItem.speaker_role" id="update_speaker_role" type="text"
              placeholder="Enter speaker's role or title"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required />
          </div>
          <div>
            <label for="update_edition_id" class="block text-sm font-medium text-gray-700">Edition ID</label>
            <input v-model="selectedItem.edition_id" id="update_edition_id" type="text" placeholder="Enter edition ID"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required />
          </div>
          <div>
            <label for="update_image" class="block text-sm font-medium text-gray-700">Profile Image</label>
            <input id="update_image" type="file" @change="handleUpdateImageUpload" accept="image/*"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
            <div v-if="updateImagePreview" class="mt-2">
              <img :src="updateImagePreview" alt="Image Preview" class="w-20 h-20 object-cover rounded-md" />
            </div>
          </div>
          <div v-if="error" class="p-2 bg-red-100 text-red-700 rounded-md flex items-center">
            <span class="mr-1">!</span> {{ error }}
          </div>
        </div>
        <div class="mt-6 flex justify-end space-x-3">
          <button @click="showUpdateModal = false" type="button"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200">
            Cancel
          </button>
          <button @click="updateKeynote" type="button"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
            Update Keynote
          </button>
        </div>
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
/* Keep all existing styles exactly the same */
.keynote-section {
  padding: 2rem;
  max-width: 1200px;
  margin: auto;
  font-family: 'Poppins', sans-serif;
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

.title {
  font-size: 1.7rem;
  margin-bottom: 1rem;
  color: black;
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

.action-buttons button {
  margin-left: 1rem;
  padding: 8px 16px;
  border-radius: 25px;
  border: 1px solid;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.3s ease;
}

.add-btn {
  border-color: #268557;
  color: #268557;
  background-color: #fff;
}

.add-btn:hover {
  background-color: #268557;
  color: #fff;
}

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

@media (max-width: 768px) {
  .nav-btn {
    width: 57px;
    height: 45px;
  }
}
</style>